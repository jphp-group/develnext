<?php
namespace ide\forms;

use ide\account\api\ServiceResponse;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\ui\Notifications;
use php\gui\framework\AbstractForm;
use php\gui\UXDialog;

class RegisterForm extends AbstractOnlineIdeForm
{
    use DialogFormMixin;

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

        if ($dialog->showDialog() && $dialog->getResult()) {
            Ide::accountManager()->setAccessToken($dialog->getResult());
            $this->hide();
            Ide::accountManager()->updateAccount();

            $this->setResult(true);
            $this->hide();
        }
    }

    /**
     * @event cancelButton.action
     */
    public function actionCancel()
    {
        $msg = new MessageBoxForm('Вы уверены, что хотите выйти из регистрации?', ['Да, выйти', 'Нет']);

        if ($msg->showDialog() && $msg->getResultIndex() == 0) {
            $this->hide();
        }
    }

    /**
     * @event nextButton.action
     */
    public function actionDone()
    {
        $this->showPreloader();

        Ide::service()->account()->registerAsync($this->emailField->text, $this->nameField->text, $this->passwordField->text, $this->captchaField->text,
            function (ServiceResponse $response) {
                if ($response->isNotSuccess()) {
                    $this->updateCaptcha();

                    if ($response->message() == 'Validation') {
                        Notifications::showInvalidValidation();
                    } else {
                        Notifications::error("Ошибка регистрации", $response->message());
                    }

                    $this->hidePreloader();

                    if ($response->data() === 'RegisterConfirm') {
                        $this->confirm();
                    }

                    return;
                }

                Notifications::show('Спасибо за регистрацию', $response->message());

                $this->confirm();
                $this->hidePreloader();
            }
        );
    }
}