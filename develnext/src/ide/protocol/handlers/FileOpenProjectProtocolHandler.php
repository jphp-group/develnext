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
        if (fs::hasExt($query, 'dnproject')) {
            ProjectSystem::open($query);
        } elseif (fs::hasExt($query, 'zip')) {
            ProjectSystem::import($query);
        }
    }
}