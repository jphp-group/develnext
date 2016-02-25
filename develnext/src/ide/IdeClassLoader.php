<?php
namespace ide;

use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\ClassLoader;
use php\lang\JavaException;
use php\lang\Module;
use php\lib\fs;
use php\lib\str;

class IdeClassLoader extends ClassLoader
{
    /**
     * @var \php\io\File
     */
    protected $cacheBytecodeDir;

    /**
     * IdeClassLoader constructor.
     */
    public function __construct()
    {
        $this->cacheBytecodeDir = $cacheBytecodeDir = Ide::getFile("cache/bytecode");
    }

    function loadClass($name)
    {
        $name = str::replace($name, '\\', '/');

        try {
            $fileCompiled = new File($this->cacheBytecodeDir, "/$name.phb");

            if ($fileCompiled->exists()) {
                try {
                    echo "LOAD cached class '$fileCompiled'", "\n";
                    $module = new Module($fileCompiled, true);
                    $module->call();
                } catch (\Exception $e) {
                    echo " ---> error \n";
                    $module = new Module(Stream::of("res://$name.php"));
                    $module->call();
                }
            } else {
                $module = new Module(Stream::of("res://$name.php"));
                $module->call();

                fs::makeDir($fileCompiled->getParent());

                $module->dump($fileCompiled, true);
            }

            return true;
        } catch (IOException $e) {
            return false;
        }
    }
}