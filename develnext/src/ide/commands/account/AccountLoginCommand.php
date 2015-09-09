<?php
namespace ide\commands\account;

use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\AbstractCommand;

/**
 * Class AccountLogoutCommand
 * @package ide\commands\account
 */
class AccountLoginCommand extends AbstractCommand
{
    public function getName()
    {
        return "Войти";
    }

    public function getCategory()
    {
        return 'account';
    }

    public function onExecute()
    {
        Ide::accountManager()->authorize(true);
    }
}