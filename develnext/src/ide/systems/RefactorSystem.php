<?php
namespace ide\systems;

use ide\Ide;
use ide\project\Project;

class RefactorSystem
{
    protected static $init = false;
    static $renameHandlers = [];

    static function init() {
        if (!static::$init) {
            static::$init = true;

            Ide::get()->on('openProject', function (Project $project) {

                foreach (static::$renameHandlers as $it) {
                    $project->getRefactorManager()->bind("rename:$it[0]", $it[1]);
                }
            });
        }
    }

    static function rename($type, $target, $newName)
    {
        static::init();

        if ($project = Ide::project()) {
            return $project->getRefactorManager()->rename($type, $target, $newName);
        }

        return 'busy';
    }

    static function onRename($type, callable $handler)
    {
        static::init();

        static::$renameHandlers[] = [$type, $handler];
    }
}