<?php
namespace ide\project\behaviours;

use develnext\lexer\inspector\PHPInspector;
use Error;
use ide\Ide;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\project\Project;
use ide\project\ProjectFile;
use ide\project\ProjectModule;
use ide\utils\FileUtils;
use ide\zip\JarArchive;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXCheckbox;
use php\gui\UXLabel;
use php\io\File;
use php\io\IOException;
use php\lang\Environment;
use php\lang\Module;
use php\lang\ThreadPool;
use php\lib\fs;
use php\lib\str;
use php\net\URL;
use php\util\LauncherClassLoader;
use php\util\Shared;
use php\util\SharedValue;

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
     * @var UXVBox
     */
    protected $uiSettings;

    /**
     * @var UXCheckbox
     */
    protected $uiByteCodeCheckbox;

    /**
     * @var PHPInspector
     */
    protected $inspector;

    /**
     * @var ThreadPool
     */
    protected $inspectorThreadPool;

    /**
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_CORE;
    }

    /**
     * @return PHPInspector
     */
    public function getInspector()
    {
        return $this->inspector;
    }

    /**
     * ...
     */
    public function inject()
    {
        $this->inspectorThreadPool = ThreadPool::createSingle();
        $this->inspector = new PHPInspector();

        $this->project->registerInspector('php', $this->inspector);

        $this->project->on('close', [$this, 'doClose']);
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('save', [$this, 'doSave']);
        $this->project->on('preCompile', [$this, 'doPreCompile']);
        $this->project->on('compile', [$this, 'doCompile']);

        $this->project->on('makeSettings', [$this, 'doMakeSettings']);
        $this->project->on('updateSettings', [$this, 'doUpdateSettings']);
    }

    protected function refreshInspector()
    {
        if ($this->inspector) {
            $this->project->loadDirectoryForInspector($this->project->getFile("src/"));

            $this->inspector->setExtensions(['source']);
            $this->project->loadDirectoryForInspector($this->project->getFile("src/"));

            $this->inspector->setExtensions(['php']);
            $this->project->loadDirectoryForInspector($this->project->getFile(self::GENERATED_DIRECTORY));
        }
    }

    public function doClose()
    {
        $this->inspectorThreadPool->shutdown();
        //$this->inspector->free();

        $this->uiSettings = null;
        $this->uiByteCodeCheckbox = null;
        $this->globalUseImports = null;
    }

    public function doOpen()
    {
        $this->project->eachSrcFile(function (ProjectFile $file) {
            if (str::endsWith($file, '.php.source')) {
                FileUtils::copyFile($file, fs::pathNoExt($file));
                fs::delete($file);
            }
        });

        $gradle = GradleProjectBehaviour::get();

        if ($gradle) {
            $config = $gradle->getConfig();
            $config->addSourceSet('main.resources.srcDirs', self::GENERATED_DIRECTORY);
        } else {
            Logger::warn("Unable to add the generated src directory to build.gradle file");
        }

        $this->project->clearIdeCache('bytecode');

        $this->refreshInspector();
    }

    public function doSave()
    {
        if ($this->uiSettings) {
            $this->setIdeConfigValue(self::OPT_COMPILE_BYTE_CODE, $this->uiByteCodeCheckbox->selected);
        }

        $this->refreshInspector();
    }

    public function doPreCompile($env, callable $log = null)
    {
        $directories = [$this->project->getSrcFile(""), $this->project->getSrcFile("", true)];

        $cacheIgnore = [];

        foreach ($directories as $directory) {
            fs::scan($directory, function ($filename) use ($directory, $log, &$cacheIgnore) {
                $name = FileUtils::relativePath($directory, $filename);

                if (fs::ext($name) == 'php') {
                    $cacheIgnore[] = $name;

                    $file = 'bytecode/' . fs::pathNoExt($name) . '.phb';

                    $this->project->clearIdeCache($file);
                }
            });
        }

        FileUtils::put($this->project->getIdeCacheFile('bytecode/.cacheignore'), str::join($cacheIgnore, "\n"));

        fs::scan($this->project->getFile(self::SOURCES_DIRECTORY), function ($filename) {
            if (fs::ext($filename) == 'phb') {
                fs::delete($filename);
            }
        });
    }

    public function isByteCodeEnabled() {
        return $this->getIdeConfigValue(self::OPT_COMPILE_BYTE_CODE, true);
    }

    protected function collectZipLibraries()
    {
        $result = [];

        foreach ($this->project->getModules() as $module) {
            switch ($module->getType()) {
                case 'zipfile':
                case 'jarfile':
                    $result[] = fs::abs($module->getId());
                    break;
            }
        }

        return $result;
    }

    public function doCompile($env, callable $log = null)
    {
        $useByteCode = Project::ENV_PROD == $env;

        if ($useByteCode && $this->isByteCodeEnabled()) {
            $scope = new Environment(null, Environment::HOT_RELOAD);
            $scope->importClass(FileUtils::class);

            $zipLibraries = $this->collectZipLibraries();

            $generatedDirectory = $this->project->getSrcFile('', true);
            $dirs = [$generatedDirectory, $this->project->getSrcFile('')];

            $includedFiles = [];

            if ($bundle = BundleProjectBehaviour::get()) {
                foreach ($bundle->fetchAllBundles($env) as $one) {
                    $dirs[] = $one->getProjectVendorDirectory();
                }
            }

            $scope->execute(function () use ($zipLibraries, $generatedDirectory, $dirs, &$includedFiles) {
                ob_implicit_flush(true);

                spl_autoload_register(function ($name) use ($zipLibraries, $generatedDirectory, $dirs, &$includedFiles) {
                    echo("Try class '$name' auto load\n");

                    foreach ($dirs as $dir) {
                        $filename = "$dir/$name.php";

                        if (fs::exists($filename)) {
                            echo "Find class '$name' in ", $filename, "\n";

                            $compiled = new File($generatedDirectory, $name . ".phb");
                            fs::ensureParent($compiled);

                            $includedFiles[FileUtils::hashName($filename)] = true;

                            $module = new Module($filename, false, true);
                            $module->dump($compiled, true);
                            return;
                        }
                    }
                    foreach ($zipLibraries as $file) {
                        if (!fs::exists($file)) {
                            echo "SKIP $file, is not exists.\n";
                            continue;
                        }

                        try {
                            $name = str::replace($name, '\\', '/');

                            $url = new URL("jar:file:///$file!/$name.php");

                            $conn = $url->openConnection();
                            $stream = $conn->getInputStream();

                            $module = new Module($stream, false);
                            $module->call();

                            $stream->close();

                            echo "Find class '$name' in ", $file, "\n";

                            $compiled = new File($generatedDirectory, $name . ".phb");

                            fs::ensureParent($compiled);

                            $module->dump($compiled, true);

                            return;
                        } catch (IOException $e) {
                            echo "[ERROR] {$e->getMessage()}\n";
                            // nop.
                        }
                    }
                });
            });

            foreach ($dirs as $i => $dir) {
                fs::scan($dir, function ($filename) use ($log, $scope, $i, $useByteCode, $generatedDirectory, $dir, &$includedFiles) {
                    $relativePath = FileUtils::relativePath($dir, $filename);

                    if ($i == 1) { // ignore src files if they exist in src_generated dir.
                        if (fs::exists($this->project->getSrcFile($relativePath, true))) {
                            return;
                        }
                    }

                    if (str::endsWith($filename, '.php')) {
                        if ($includedFiles[FileUtils::hashName($filename)]) {
                            return;
                        }

                        $filename = fs::normalize($filename);

                        if ($log) {
                            $log(":compile $filename");
                        }

                        $compiledFile = new File($generatedDirectory, '/' . fs::pathNoExt($relativePath) . '.phb');

                        if ($compiledFile->getParentFile() && !$compiledFile->getParentFile()->isDirectory()) {
                            $compiledFile->getParentFile()->mkdirs();
                        }

                        $includedFiles[FileUtils::hashName($filename)] = true;
                        $scope->execute(function () use ($filename, $compiledFile) {
                            $module = new Module($filename, false, true);
                            $module->dump($compiledFile, true);
                        });
                    }
                });
            }

            fs::scan($generatedDirectory, function ($filename) use ($log, $scope, $useByteCode, &$includedFiles) {
                if (fs::ext($filename) == 'php') {
                    if ($includedFiles[FileUtils::hashName($filename)]) {
                        return;
                    }

                    $filename = fs::normalize($filename);

                    if ($log) $log(":compile $filename");

                    $compiledFile = fs::pathNoExt($filename) . '.phb';

                    $includedFiles[FileUtils::hashName($filename)] = true;
                    $scope->execute(function () use ($filename, $compiledFile) {
                        $module = new Module($filename, false, true);
                        $module->dump($compiledFile);
                    });
                    fs::delete($filename);
                }
            });

            foreach ($zipLibraries as $library) {
                if (!fs::exists($library)) {
                    continue;
                }

                $jar = new JarArchive($library);

                foreach ($jar->getEntries() as $entry) {
                    if (str::startsWith($entry->getName(), 'JPHP-INF/')) {
                        continue;
                    }

                    if (fs::ext($entry->getName()) == 'php') {
                        $compiled = new File($generatedDirectory, '/' . fs::pathNoExt($entry->getName()) . ".phb");

                        if (!$compiled->exists()) {
                            if ($compiled->getParentFile() && !$compiled->getParentFile()->isDirectory()) {
                                $compiled->getParentFile()->mkdirs();
                            }

                            $stream = $jar->getEntryStream($entry->getName());
                            $className = fs::pathNoExt($entry->getName());
                            $className = str::replace($className, '/', '\\');

                            try {
                                $done = $scope->execute(function () use ($stream, $compiled, $className, $log) {
                                    if (!class_exists($className, false)) {
                                        try {
                                            $module = new Module($stream, false);
                                            $module->dump($compiled, true);
                                            return true;
                                        } catch (Error $e) {
                                            if ($log) {
                                                $log("[ERROR] Unable to compile '{$className}', {$e->getMessage()}, on line {$e->getLine()}");
                                                return false;
                                            }
                                        }
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

    public function doUpdateSettings(CommonProjectControlPane $editor = null)
    {
        if ($this->uiSettings) {
            $this->uiByteCodeCheckbox->selected = $this->getIdeConfigValue(self::OPT_COMPILE_BYTE_CODE, true);
        }
    }

    public function doMakeSettings(CommonProjectControlPane $editor)
    {
        $title = new UXLabel('Исходный php код:');
        $title->font = $title->font->withBold();

        $opts = new UXHBox();
        $opts->spacing = 5;

        $this->uiByteCodeCheckbox = $byteCodeCheckbox = new UXCheckbox('Компилировать в байткод (+ защита от декомпиляции)');
        $this->uiByteCodeCheckbox->on('mouseUp', [$this, 'doSave']);
        $byteCodeCheckbox->tooltipText = 'Компиляция будет происходить только во время итоговой сборки проекта.';
        $opts->add($byteCodeCheckbox);

        $ui = new UXVBox([$title, $opts]);
        $ui->spacing = 5;
        $this->uiSettings = $ui;


        $editor->addSettingsPane($ui);
    }
}