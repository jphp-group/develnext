<?php

use facade\Json;
use php\format\JsonProcessor;
use php\gui\UXApplication;
use php\io\Stream;
use php\lang\ClassLoader;
use php\lang\Environment;
use php\lang\SourceMap;
use php\lib\str;

define('DEVELNEXT_PROJECT_DEBUG', true);

try {
    Stream::putContents("application.pid", UXApplication::getPid());
} catch (\php\io\IOException $e) {
    exit(1);
}

class DebugClassLoader extends ClassLoader
{
    public function loadClass($name)
    {
        $name = str::replace($name, '\\', '/');

        $filename = "res://$name.php";

        if (Stream::exists($filename)) {
            require $filename;

            $this->tryLoadSourceMap($filename);
        }
    }

    public function tryLoadSourceMap($filename)
    {
        $sourceMapFile = $filename . ".sourcemap";

        if (Stream::exists($sourceMapFile)) {
            $map = (array) Json::fromFile($sourceMapFile);

            if ($map) {
                $sourceMap = new SourceMap($filename);

                foreach ($map as $cLine => $sLine) {
                    $sourceMap->addLine($sLine, $cLine);
                }

                Environment::current()->registerSourceMap($sourceMap);
            }
        }
    }
}

$debugClassLoader = new DebugClassLoader();
$debugClassLoader->register(true);