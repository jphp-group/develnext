<?php
namespace ide\systems;

use ide\Ide;
use ide\project\ProjectFile;
use ide\utils\FileUtils;
use php\gui\designer\FileSystemWatcher;
use php\gui\UXApplication;
use php\io\File;
use php\io\IOException;
use php\lang\IllegalStateException;
use php\lang\InterruptedException;
use php\lang\Thread;

class WatcherSystem
{
    /** @var FileSystemWatcher[] */
    protected static $watchers = [];

    /** @var Thread[] */
    protected static $threads = [];

    /** @var callable[] */
    protected static $handlers = [];

    protected static $enabled = true;

    static function addListener($handler)
    {
        static::$handlers[] = $handler;
    }

    static function clearListeners()
    {
        static::$handlers = [];
    }

    static function trigger($event)
    {
        if (static::$enabled) {
            UXApplication::runLater(function () use ($event) {
                $project = Ide::get()->getOpenedProject();

                $file = $project ? new ProjectFile($project, $event['context']) : null;

                foreach (static::$handlers as $handler) {
                    $handler($file, $event);
                }
            });
        }
    }

    static function off()
    {
        static::$enabled = false;
    }

    static function on()
    {
        static::$enabled = true;
    }

    static function clear()
    {
        foreach (static::$watchers as $watcher) {
            $watcher->close();
        }

        static::$watchers = [];
        static::$threads = [];
    }

    static function removePath($path, $close = true)
    {
        $hashName = FileUtils::hashName($path);

        $watcher = static::$watchers[$hashName];

        if ($close && $watcher) {
            $watcher->close();
        }

        unset(static::$threads[$hashName], $watcher);
    }

    static function addPathRecursive($path)
    {
        static::addPath($path, true);

        foreach (File::of($path)->findFiles() as $file) {
            if ($file->isDirectory()) {
                static::addPathRecursive($file);
            }
        }
    }

    static function addPath($path, $appendCreatedPath = false)
    {
        try {
            $watcher = new FileSystemWatcher($path);
        } catch (IOException $e) {
            return false;
        }

        $hashName = FileUtils::hashName($path);

        if (static::$watchers[$hashName]) {
            return false;
        }

        static::$watchers[$hashName] = $watcher;

        $thread = new Thread(function () use ($watcher, $path, $appendCreatedPath) {
            while (true) {
                try {
                    $key = $watcher->take();

                    $events = $key->pollEvents();

                    foreach ($events as $event) {
                        static::trigger($event);

                        if ($appendCreatedPath && File::of($event['context'])->isDirectory()) {
                            switch ($event['kind']) {
                                case 'ENTRY_CREATE':
                                    static::addPath($event['context']);
                                    break;

                                case 'ENTRY_DELETE':
                                    static::removePath($event['context']);
                                    break;
                            }
                        }
                    }

                    if (!$key->reset()) {
                        break;
                    }
                } catch (InterruptedException $e) {
                    break;
                } catch (IllegalStateException $e) {
                    break;
                }
            }

            static::removePath($path, false);
        });


        static::$threads[$hashName] = $thread;

        $thread->start();

        return true;
    }

    static function shutdown()
    {
        foreach (static::$watchers as $watcher) {
            $watcher->close();
        }
    }
}