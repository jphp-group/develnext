<?php
namespace ide\commands\account;

use ide\forms\MessageBoxForm;
use ide\forms\RegisterForm;
use ide\Ide;
use ide\misc\AbstractCommand;

class AccountRegisterCommand extends AbstractCommand
{
    public function getName()
    {
        return "Зарегистрироваться";
    }

    public function getCategory()
    {
        return 'account';
    }

    public function onExecute()
    {
        $dialog = new RegisterForm();
        $dialog->showAndWait();
    }
}