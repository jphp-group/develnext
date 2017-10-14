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
use php\time\Timer;

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
        $this->cacheBytecodeDir = $cacheBytecodeDir = IdeSystem::getByteCodeCacheDir();
        $versionFile = new File($cacheBytecodeDir, "/version");

        try {
            $cacheVersion = Stream::getContents($versionFile);
        } catch (IOException $e) {
            $cacheVersion = false;
        }

        $this->cache = $cache;
        $this->version = $version;


        if ($this->cache) {
            $lockFile = new File($cacheBytecodeDir, "/ide.lock");

            $updateLockFile = function () use ($lockFile) {
                if (class_exists(Ide::class, false)) {
                    if (!Ide::isCreated() || !Ide::get()->isIdle()) {
                        try {
                            Logger::debug("Update cache lock file: $lockFile");
                            Stream::putContents($lockFile, Time::millis());
                        } catch (IOException $e) {
                            Logger::warn("Failed to update cache lock file: $lockFile, {$e->getMessage()}");
                        }
                    }
                }
            };

            Timer::after('1s', $updateLockFile);

            Timer::every('7s', function (Timer $self) use ($updateLockFile) {
                if (Ide::get()->isShutdown()) {
                    $self->cancel();
                } else {
                    $updateLockFile();
                }
            });

            echo "LOADER cached version = $cacheVersion\n\nLOADER new version    = $version", "\n";
        }

        if ($this->version != $cacheVersion || !$cacheVersion) {
            $this->reloadCache = true;
            echo "LOADER reloading ...\n";
            fs::clean($cacheBytecodeDir);
            fs::makeDir($cacheBytecodeDir);

            try {
                Stream::putContents($versionFile, $this->version);
            } catch (IOException $e) {
                $this->cache = false;
                ; // nop.
            }
        }
    }

    public function clearByteCodeCache()
    {
        fs::clean($this->cacheBytecodeDir);
        fs::delete($this->cacheBytecodeDir);
    }

    public function invalidateByteCodeCache()
    {
        if (class_exists(Logger::class, false)) {
            Logger::info("Start invalidate byte code cache");
        }

        $dirs = fs::scan(
            IdeSystem::getFile("cache/"), ['excludeFiles' => true, 'namePattern' => '^bytecode_v.*'], 1
        );

        foreach ($dirs as $dir) {
            fs::delete("$dir/version");

            if (class_exists(Logger::class, false)) {
                Logger::info("Invalidate byte code cache: $dir");
            }
        }

        if (class_exists(Logger::class, false)) {
            Logger::info("Finish invalidate byte code cache");
        }
    }

    public function addClassPath($path)
    {
        if (class_exists(Logger::class, false)) {
            Logger::info("Add class path: $path");
        }

        $this->classPaths[FileUtils::hashName($path)] = FileUtils::normalizeName($path);
    }

    public function removeClassPath($path)
    {
        if (class_exists(Logger::class, false)) {
            Logger::info("Remove class path: $path");
        }

        unset($this->classPaths[FileUtils::hashName($path)]);
    }

    protected function loadClassFromPath($name, File $fileCompiled = null)
    {
        foreach ([""] + $this->classPaths as $path) {
            try {
                $t = Time::millis();

                $filename = "res://$path/$name.php";

                /*if (class_exists(Logger::class, false)) {
                    Logger::warn("Make module '$filename'");
                }*/

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