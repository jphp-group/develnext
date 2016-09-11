<?php
namespace ide\commands\account;

use ide\editors\AbstractEditor;
use ide\forms\AccountProfileEditForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\gui\UXImageArea;
use php\gui\UXMenuItem;
use php\lang\IllegalStateException;
use script\TimerScript;

/**
 * Class AccountLogoutCommand
 * @package ide\commands\account
 *
 */
class AccountInfoCommand extends AbstractCommand
{
    public function getName()
    {
        return "Редактировать";
    }

    public function getIcon()
    {
        return 'icons/accountEdit16.png';
    }

    public function getCategory()
    {
        return 'account';
    }

    public function withAfterSeparator()
    {
        return false;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $dialog = new AccountProfileEditForm();
        $dialog->showAndWait();
    }
}