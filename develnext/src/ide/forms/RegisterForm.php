<?php
namespace ide\forms;

use ide\account\api\ServiceResponse;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\UXDialog;

class RegisterForm extends AbstractForm
{
    protected function init()
    {
        parent::init();

        $this->icon->image = Ide::get()->getImage('DevelNextIco.png')->image;
    }

    /**
     * @event refreshCaptcha.action
     */
    public function updateCaptcha()
    {
        $this->captcha->image = Ide::service()->account()->captcha();
    }

    /**
     * @event show
     */
    public function actionShow()
    {
        $this->updateCaptcha();
    }

    /**
     * @event confirmLink.action
     */
    public function confirm()
    {
        $dialog = new RegisterConfirmForm();
        $dialog->setEmail($this->emailField->text);

        if ($dialog->showDialog()) {
            Ide::accountManager()->setAccessToken($dialog->getResult());
            $this->hide();
        }
    }

    /**
     * @event nextButton.action
     */
    public function actionDone()
    {
        $this->showPreloader();

        Ide::service()->account()->registerAsync($this->emailField->text, $this->passwordField->text, $this->captchaField->text,
            function (ServiceResponse $response) {
                if ($response->isNotSuccess()) {
                    $this->updateCaptcha();
                    UXDialog::show($response->message(), 'ERROR');
                    $this->hidePreloader();
                    return;
                }

                UXDialog::show($response->message());
                $this->confirm();
                $this->hidePreloader();
            }
        );
    }
}