<?php
namespace php\framework;

use php\io\ResourceStream;
use php\io\Stream;
use php\lang\Package;
use php\lang\PackageLoader;
use php\lib\fs;
use php\lib\str;
use php\util\Scanner;

/**
 * Class FrameworkPackageLoader
 * @package php\framework
 *
 * @packages framework
 */
class FrameworkPackageLoader extends PackageLoader
{
    public static function makeFrom($fileOrStream)
    {
        $pkg = new Package();
        $type = 0;

        $sc = new Scanner($fileOrStream instanceof Stream ? $fileOrStream : fs::get($fileOrStream));

        $classes = [];
        $functions = [];
        $constants = [];

        while ($sc->hasNextLine()) {
            $line = str::trim($sc->nextLine());

            if ($line) {
                if ($line[0] == '[') {
                    switch (str::trim($line)) {
                        case "[classes]":
                            $type = 1;
                            break;
                        case "[functions]":
                            $type = 2;
                            break;
                        case "[constants]":
                            $type = 3;
                            break;
                    }
                } else {
                    switch ($type) {
                        case 1:
                            $classes[] = $line;
                            break;
                        case 2:
                            $functions[] = $line;
                            break;
                        case 3:
                            $constants[] = $line;
                            break;

                    }
                }
            }
        }

        if ($classes) {
            $pkg->addClasses($classes);
        }

        if ($functions) {
            $pkg->addFunctions($functions);
        }

        if ($constants) {
            $pkg->addConstants($constants);
        }

        return $pkg;
    }

    /**
     * @param string $name
     * @return Package
     * @throws \Exception
     */
    public function load($name)
    {
        $file = "res://.packages/$name.php";

        if (ResourceStream::exists($file)) {
            require $file;
        }

        $file = "res://.packages/$name.pkg";

        if (ResourceStream::exists($file)) {
            Logger::debug("load '$name' package.");

            return self::makeFrom($file);
        } else {
            Logger::warn("package '$name' is not found.");
        }

        return null;
    }
}