<?php

namespace ide\forms;

use ide\account\api\AccountService;
use ide\account\api\ServiceResponse;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\Logger;
use ide\ui\Notifications;
use ide\utils\UiUtils;
use php\gui\UXDesktop;
use php\gui\framework\AbstractForm;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXImage;
use php\gui\UXPasswordField;
use php\gui\UXTextField;

/**
 * Class LoginForm
 * @package ide\forms
 *
 * @property UXImage $icon
 * @property UXTextField $emailField
 * @property UXPasswordField $passwordField
 * @property UXButton $loginButton
 * @property UXButton $loginVkButton
 * @property UXHyperlink $registerLink
 * @property UXHyperlink $forgetPasswordLink
 */
class LoginForm extends AbstractOnlineIdeForm
{
    //use SavableFormMixin;

    protected function init()
    {
        parent::init();

        $this->icon->image = Ide::get()->getImage('DevelNextIco.png')->image;
    }

    /**
     * @event loginButton.action
     * @event emailField.keyDown-Enter
     * @event passwordField.keyDown-Enter
     */
    public function actionLogin()
    {
        $this->showPreloader('Подождите ...');

        Ide::service()->account()->authAsync($this->emailField->text, $this->passwordField->text,
            function (ServiceResponse $response) {
                $this->hidePreloader();

                if ($response->isSuccess()) {
                    if ($response->result() == 'RegisterConfirm') {
                        $dialog = new RegisterConfirmForm();
                        $dialog->setEmail($this->emailField->text);

                        if ($dialog->showDialog() && $dialog->getResult()) {
                            Ide::accountManager()->setAccessToken($dialog->getResult());
                            $this->hide();
                            Ide::accountManager()->updateAccount();
                            $this->hide();
                            return;
                        }
                    }

                    Ide::accountManager()->setAccessToken($response->result('id'));
                    $this->hide();
                } else {
                    $message = $response->message();

                    switch ($message) {
                        case 'PasswordInvalid':
                            $message = 'Введите корректный пароль';
                            $this->showError($message, $this->passwordField);
                            break;
                        case 'LoginInvalid';
                            $message = 'Введите корректный email или логин';
                            $this->showError($message, $this->emailField);
                            break;
                        case 'NotFoundLogin':
                            $message = 'Аккаунт с таким логином или email не зарегистрирован';
                            $this->showError($message, $this->emailField);
                            break;
                        case 'AccessDenied':
                            $message = 'Вы ввели некорректный пароль';
                            $this->showError($message, $this->passwordField);
                            break;
                    }

                    Notifications::error('Ошибка входа', $message);
                }
            }
        );
    }

    /**
     * @event registerLink.action
     */
    public function actionRegister()
    {
        $registerForm = new RegisterForm();

        if ($registerForm->showDialog() && $registerForm->getResult()) {
            $this->hide();
        }
    }

    /**
     * @event forgetPasswordLink.action
     */
    public function actionRestore()
    {
        $dialog = new AccountRestorePasswordForm();
        $dialog->showAndWait();
    }
}