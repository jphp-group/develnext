<?php

use facade\Json;
use php\format\JsonProcessor;
use php\framework\Logger;
use php\gui\UXApplication;
use php\io\Stream;
use php\lang\ClassLoader;
use php\lang\Environment;
use php\lang\SourceMap;
use php\lib\str;
use php\time\Time;

define('DEVELNEXT_PROJECT_DEBUG', true);

try {
    Stream::putContents("application.pid", UXApplication::getPid());
} catch (\php\io\IOException $e) {
    exit(1);
}

Logger::setLevel(Logger::LEVEL_DEBUG);

class DebugClassLoader extends ClassLoader
{
    /**
     * @var int
     */
    protected $allTime = 0;

    public function loadClass($name)
    {
        $name = str::replace($name, '\\', '/');

        $filename = "res://$name.php";

        if (Stream::exists($filename)) {
            $this->tryLoadSourceMap($filename);

            $t = Time::millis();
            require $filename;
            $t = Time::millis() - $t;
            $this->allTime += $t;

           // echo "[DEBUG] require '$filename', $t ms ($this->allTime ms)\n";
            echo "[DEBUG] load '$filename', $t ms\n";
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