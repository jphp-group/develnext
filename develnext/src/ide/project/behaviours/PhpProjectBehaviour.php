<?php
namespace ide\project\behaviours;

use ide\editors\ProjectEditor;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\Project;
use ide\project\ProjectFile;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
use ide\zip\JarArchive;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXCheckbox;
use php\gui\UXLabel;
use php\io\File;
use php\io\IOException;
use php\lang\Environment;
use php\lang\Module;
use php\lib\fs;
use php\lib\str;
use php\net\URL;
use php\util\LauncherClassLoader;

/**
 * Class PhpProjectBehaviour
 * @package ide\project\behaviours
 */
class PhpProjectBehaviour extends AbstractProjectBehaviour
{
    const OPT_COMPILE_BYTE_CODE = 'compileByteCode';

    const SOURCES_DIRECTORY = 'src/app';
    const GENERATED_DIRECTORY = 'src_generated';

    /**
     * @var array
     */
    protected $globalUseImports = [];

    /**
     * @var array
     */
    protected $externalJarLibraries = [];

    /**
     * @var UXVBox
     */
    protected $uiSettings;

    /**
     * @var UXCheckbox
     */
    protected $uiByteCodeCheckbox;

    /**
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_CORE;
    }

    /**
     * ...
     */
    public function inject()
    {
        $this->project->setSrcDirectory('src');
        $this->project->setSrcGeneratedDirectory(self::GENERATED_DIRECTORY);

        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('save', [$this, 'doSave']);
        $this->project->on('preCompile', [$this, 'doPreCompile']);
        $this->project->on('compile', [$this, 'doCompile']);

        $this->project->on('makeSettings', [$this, 'doMakeSettings']);
        $this->project->on('updateSettings', [$this, 'doUpdateSettings']);
    }

    public function doOpen()
    {
        $gradle = GradleProjectBehaviour::get();

        if ($gradle) {
            $config = $gradle->getConfig();
            $config->addSourceSet('main.resources.srcDirs', self::GENERATED_DIRECTORY);
        }
    }

    public function doSave()
    {
        if ($this->uiSettings) {
            $this->setIdeConfigValue(self::OPT_COMPILE_BYTE_CODE, $this->uiByteCodeCheckbox->selected);
        }
    }

    public function doPreCompile()
    {
        $generatedDirectory = $this->project->getFile(self::GENERATED_DIRECTORY);

        FileUtils::deleteDirectory($generatedDirectory);
        $generatedDirectory->mkdirs();

        FileUtils::scan($this->project->getFile(self::SOURCES_DIRECTORY), function ($filename) {
            if (str::endsWith($filename, '.php.sourcemap')) {
                fs::delete($filename);
            }

            if (fs::ext($filename) == 'phb') {
                fs::delete($filename);
            }
        });
    }

    public function doCompile($env, callable $log = null)
    {
        $useByteCode = Project::ENV_PROD == $env;

        if ($useByteCode && $this->getIdeConfigValue(self::OPT_COMPILE_BYTE_CODE)) {
            $scope = new Environment(null, Environment::HOT_RELOAD);

            $jarLibraries = $this->externalJarLibraries;

            $sourceDir = $this->project->getFile('src/');
            $generatedDirectory = $this->project->getFile(self::GENERATED_DIRECTORY);

            $scope->execute(function () use ($jarLibraries, $sourceDir, $generatedDirectory) {
                ob_implicit_flush(true);

                spl_autoload_register(function ($name) use ($jarLibraries, $sourceDir, $generatedDirectory) {
                    foreach ($jarLibraries as $file) {
                        if (!fs::exists($file)) {
                            echo "SKIP $file, is not exists.\n";
                            continue;
                        }

                        try {
                            $name = str::replace($name, '\\', '/');

                            $url = new URL("jar:file:/$file!/$name.php");

                            $conn = $url->openConnection();
                            $stream = $conn->getInputStream();

                            $module = new Module($stream, false);
                            $module->call();

                            $stream->close();

                            echo "Find class '$name' in ", $file, "\n";

                            $compiled = new File($generatedDirectory, $name . ".phb");

                            if ($compiled->getParentFile() && !$compiled->getParentFile()->isDirectory()) {
                                $compiled->getParentFile()->mkdirs();
                            }

                            $module->dump($compiled, true);

                            return;
                        } catch (IOException $e) {
                            // nop.
                        }
                    }
                });
            });

            FileUtils::scan($this->project->getFile(self::SOURCES_DIRECTORY), function ($filename) use ($log, $scope, $useByteCode, $generatedDirectory) {
                if (str::endsWith($filename, '.php')) {
                    $filename = fs::normalize($filename);

                    if ($log) {
                        $log(":compile $filename");
                    }

                    $file = $this->project->getAbsoluteFile($filename);
                    $relativePath = $file->getRelativePath('src');
                    $compiledFile = new File($generatedDirectory, '/' . FileUtils::stripExtension($relativePath) . '.phb');

                    if ($compiledFile->getParentFile() && !$compiledFile->getParentFile()->isDirectory()) {
                        $compiledFile->getParentFile()->mkdirs();
                    }

                    $scope->execute(function () use ($filename, $compiledFile) {
                        $module = new Module($filename, false, true);
                        $module->dump($compiledFile, true);
                    });
                }
            });

            foreach ($this->externalJarLibraries as $library) {
                if (!fs::exists($library)) {
                    continue;
                }

                $jar = new JarArchive($library);

                foreach ($jar->getEntries() as $entry) {
                    if (str::startsWith($entry->getName(), 'JPHP-INF/')) {
                        continue;
                    }

                    if (fs::ext($entry->getName()) == 'php') {
                        $compiled = new File($generatedDirectory, '/' . FileUtils::stripExtension($entry->getName()) . ".phb");

                        if (!$compiled->exists()) {
                            if ($compiled->getParentFile() && !$compiled->getParentFile()->isDirectory()) {
                                $compiled->getParentFile()->mkdirs();
                            }

                            $stream = $jar->getEntryStream($entry->getName());
                            $className = FileUtils::stripExtension($entry->getName());
                            $className = str::replace($className, '/', '\\');

                            try {
                                $done = $scope->execute(function () use ($stream, $compiled, $className) {
                                    if (!class_exists($className, false)) {
                                        $module = new Module($stream, false);
                                        $module->dump($compiled, true);
                                        return true;
                                    }

                                    return false;
                                });

                                if ($log && $done) {
                                    $log(":compile {$entry->getName()}");
                                }
                            } finally {
                                $stream->close();
                            }
                        }
                    }
                }
            }
        }
    }

    public function addExternalJarLibrary($file)
    {
        $this->externalJarLibraries[FileUtils::hashName($file)] = $file;
    }


    public function doUpdateSettings(ProjectEditor $editor = null)
    {
        if ($this->uiSettings) {
            $this->uiByteCodeCheckbox->selected = $this->getIdeConfigValue(self::OPT_COMPILE_BYTE_CODE, false);
        }
    }

    public function doMakeSettings(ProjectEditor $editor)
    {
        $title = new UXLabel('Исходный код:');
        $title->font = $title->font->withBold();

        $opts = new UXHBox();
        $opts->spacing = 5;

        $this->uiByteCodeCheckbox = $byteCodeCheckbox = new UXCheckbox('Компилировать в байткод');
        $this->uiByteCodeCheckbox->on('mouseUp', [$this, 'doSave']);
        $byteCodeCheckbox->tooltipText = 'Компиляция будет происходить только во время итоговой сборки проекта.';
        $opts->add($byteCodeCheckbox);

        $ui = new UXVBox([$title, $opts]);
        $ui->spacing = 5;
        $this->uiSettings = $ui;


        $editor->addSettingsPane($ui);
    }
}