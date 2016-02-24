<?php
namespace ide\project\behaviours;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\ProjectFile;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
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
    const SOURCES_DIRECTORY = 'src/app';

    /**
     * @var array
     */
    protected $globalUseImports = [];

    /**
     * @var array
     */
    protected $externalJarLibraries = [];

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('compile', [$this, 'doCompile']);
    }

    public function doOpen()
    {
    }

    public function doCompile($env, callable $log = null)
    {
        if ($this->globalUseImports) {
            $imports = [];

            foreach ($this->globalUseImports as $import) {
                $imports[] = [$import];
            }

            $scope = new Environment(null);
            $jarLibraries = $this->externalJarLibraries;

            $sourceDir = $this->project->getFile('src/');

            $scope->execute(function () use ($jarLibraries, $sourceDir) {
                ob_implicit_flush(true);

                spl_autoload_register(function ($name) use ($jarLibraries, $sourceDir) {
                    foreach ($jarLibraries as $file) {
                        try {
                            $name = str::replace($name, '\\', '/');

                            $url = new URL("jar:file:/$file!/$name.php");

                            echo "Search in class in ", $file, "\n";

                            $conn = $url->openConnection();
                            $stream = $conn->getInputStream();

                            $module = new Module($stream, false);
                            $module->call();

                            $stream->close();

                            $compiled = new File($sourceDir, $name . ".phb");

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

            FileUtils::scan($this->project->getFile(self::SOURCES_DIRECTORY), function ($filename) use ($imports, $log, $scope) {
                if (str::endsWith($filename, '.php')) {
                    $phpParser = new PhpParser(FileUtils::get($filename));

                    $phpParser->addUseImports($imports);

                    if ($log) {
                        $filename = fs::normalize($filename);
                        $log(":import use '$filename'");
                    }

                    FileUtils::put($filename, $phpParser->getContent());

                    $scope->execute(function () use ($filename) {
                        $module = new Module($filename, false, true);
                        $module->dump(fs::parent($filename) . '/' . fs::nameNoExt($filename) . '.phb', true);
                    });
                }
            });
        }
    }

    public function clearGlobalUseImports()
    {
        $this->globalUseImports = [];
    }

    public function addGlobalUseImport($class)
    {
        $this->globalUseImports[$class] = $class;
    }

    public function addExternalJarLibrary($file)
    {
        $this->externalJarLibraries[FileUtils::hashName($file)] = $file;
    }
}