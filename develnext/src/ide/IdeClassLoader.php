<?php
namespace ide;

use ide\systems\IdeSystem;
use ide\utils\FileUtils;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\ClassLoader;
use php\lang\IllegalStateException;
use php\lang\JavaException;
use php\lang\Module;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;
use php\time\Time;

class IdeClassLoader extends ClassLoader
{
    const VERSION = 1;

    /**
     * @var \php\io\File
     */
    protected $cacheBytecodeDir;

    /**
     * @var bool
     */
    protected $cache;

    /**
     * @var bool
     */
    protected $reloadCache = false;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var
     */
    protected $needToDumpClasses;

    /**
     * @var array
     */
    protected $classPaths = [];

    /**
     * IdeClassLoader constructor.
     * @param bool $cache
     * @param null $version
     * @throws IllegalStateException
     */
    public function __construct($cache = true, $version = null)
    {
        $this->cacheBytecodeDir = $cacheBytecodeDir = IdeSystem::getFile("cache/bytecode_v" . self::VERSION);
        $versionFile = new File($cacheBytecodeDir, "/version");

        try {
            $cacheVersion = Stream::getContents($versionFile);
        } catch (IOException $e) {
            $cacheVersion = false;
        }

        $this->cache = $cache;
        $this->version = $version;


        if ($this->cache) {
            echo "LOADER cached version = $cacheVersion\n\nLOADER new version    = $version", "\n";
        }

        if ($this->version != $cacheVersion || !$cacheVersion) {
            $this->reloadCache = true;
            echo "LOADER reloading ...\n";
            FileUtils::deleteDirectory($cacheBytecodeDir);
            $cacheBytecodeDir->mkdirs();

            try {
                Stream::putContents($versionFile, $this->version);
            } catch (IOException $e) {
                $this->cache = false;
                ; // nop.
            }
        }
    }

    public function addClassPath($path)
    {
        $this->classPaths[FileUtils::hashName($path)] = FileUtils::normalizeName($path);
    }

    public function removeClassPath($path)
    {
        unset($this->classPaths[FileUtils::hashName($path)]);
    }

    protected function loadClassFromPath($name, File $fileCompiled = null)
    {
        foreach ([""] + $this->classPaths as $path) {
            try {
                $t = Time::millis();

                $filename = "res://$path/$name.php";
                $module = new Module(Stream::of($filename));
                $module->call();

                $t = Time::millis() - $t;

                if (class_exists(Logger::class, false)) {
                    if ($t > 150) {
                        Logger::warn("Loading '$filename' takes a long time, $t ms");
                    }
                }

                if ($this->cache && $fileCompiled) {
                    fs::makeDir($fileCompiled->getParent());
                    $module->dump($fileCompiled, true);
                }

                $module->cleanData();
                break;
            } catch (IOException $e) {
                continue;
            }
        }
    }

    function loadClass($name)
    {
        $name = str::replace($name, '\\', '/');

        try {
            $fileCompiled = new File($this->cacheBytecodeDir, "/$name.phb");

            if ($fileCompiled->exists() && $this->cache && !$this->reloadCache) {
                try {
                    echo "LOAD compiled '$name.phb'", "\n";
                    $module = new Module($fileCompiled, true);
                    $module->call();
                } catch (\Exception $e) {
                    echo " ---> error \n";
                    $this->loadClassFromPath($name);
                }
            } else {
                $this->loadClassFromPath($name, $fileCompiled);
            }

            return true;
        } catch (IOException $e) {
            return false;
        }
    }
}