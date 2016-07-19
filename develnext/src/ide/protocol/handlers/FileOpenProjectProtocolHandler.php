<?php
namespace ide\protocol\handlers;

use ide\account\api\ServiceResponse;
use ide\commands\AbstractProjectCommand;
use ide\forms\MessageBoxForm;
use ide\forms\SharedProjectDetailForm;
use ide\Ide;
use ide\Logger;
use ide\protocol\AbstractProtocolHandler;
use ide\systems\ProjectSystem;
use ide\ui\Notifications;
use php\lib\fs;
use php\lib\str;

class FileOpenProjectProtocolHandler extends AbstractProtocolHandler
{
    /**
     * @param string $query
     * @return bool
     */
    public function isValid($query)
    {
        return true;
    }

    /**
     * @param $query
     * @return bool
     */
    public function handle($query)
    {
        Logger::info("Trigger open $query");

        if (fs::hasExt($query, 'dnproject')) {
            Ide::get()->disableOpenLastProject();

            Ide::get()->bind('start', function () use ($query) {
                ProjectSystem::open($query);
            });
        } elseif (fs::hasExt($query, 'zip')) {
            Ide::get()->disableOpenLastProject();

            Ide::get()->bind('start', function () use ($query) {
                ProjectSystem::import($query);
            });
        }
    }
}