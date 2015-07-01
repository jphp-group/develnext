<?php
namespace php\gui\designer;
use php\lang\InterruptedException;

/**
 * Example:
 *
 *  $watcher = new FileSystemWatcher('path/to');
 *
 *  while (true) {
 *      try {
 *          $key = $watcher->take();
 *
 *          foreach ($key->pollEvents() as $event) {
 *              $event['kind'], $event['context'], $event['count']
 *          }
 *
 *          if (!$key->reset()) {
 *              break;
 *          }
 *
 *      } catch (InterruptedException $e) {
 *          break;
 *      }
 *  }
 *
 *  ...
 *  $watcher->close();
 *
 * Class FileSystemWatcher
 * @package php\gui\designer
 */
class FileSystemWatcher
{
    /**
     * @param string $path
     */
    public function __construct($path)
    {
    }

    /**
     * @return WatchFileKey
     * @throws InterruptedException
     */
    public function take()
    {
    }

    public function close()
    {
    }
}