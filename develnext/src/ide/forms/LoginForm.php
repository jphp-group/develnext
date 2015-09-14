<?php
namespace ide\forms;

use ide\account\api\AccountService;
use ide\account\api\ServiceResponse;
use ide\Ide;
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
class LoginForm extends AbstractForm
{
    /**
     * @var AccountService
     */
    protected $accountService;

    protected function init()
    {
        parent::init();

        $this->accountService = new AccountService();

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

        $this->accountService->authAsync($this->emailField->text, $this->passwordField->text,
            function (ServiceResponse $response) {
                if ($response->isSuccess()) {
                    UXDialog::show($response->message());

                    Ide::accountManager()->setAccessToken($response->data());
                    $this->hide();
                } else {
                    UXDialog::show($response->message(), 'ERROR');
                }

                $this->hidePreloader();
            }
        );
    }

    /**
     * @event loginVkButton.action
     */
    public function actionLoginVk()
    {
        $this->showPreloader('Входим через VK ...');

        $this->accountService->authVkAsync(function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                $redirectForm = new LoginVkRedirectForm();

                $url = $response->data();

                $redirectForm->setAuthUrl($url);
                if ($redirectForm->showDialog()) {
                    Ide::accountManager()->setAccessToken($redirectForm->getResult());
                    $this->hide();
                }
            }

            $this->hidePreloader();
        });
    }

    /**
     * @event registerLink.action
     */
    public function actionRegister()
    {
        $registerForm = new RegisterForm();
        $registerForm->showAndWait();
    }
}