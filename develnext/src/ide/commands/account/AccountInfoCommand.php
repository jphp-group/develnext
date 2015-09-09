<?php
namespace ide\commands\account;

use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\AbstractCommand;

/**
 * Class AccountLogoutCommand
 * @package ide\commands\account
 */
class AccountInfoCommand extends AbstractCommand
{
    public function getName()
    {
        return "Аккаунт (" . Ide::accountManager()->getAccountEmail() . ")";
    }

    public function getCategory()
    {
        return 'account';
    }

    public function makeMenuItem()
    {
        $item = parent::makeMenuItem();
        $item->disable = true;

        return $item;
    }

    public function onExecute()
    {
        ;
    }
}