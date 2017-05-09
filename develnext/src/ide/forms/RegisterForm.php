<?php
namespace ide\forms;

use ide\account\api\ServiceResponse;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\ui\Notifications;
use php\gui\framework\AbstractForm;
use php\gui\UXDialog;
use php\gui\UXNode;
use php\gui\UXTextField;
use php\gui\UXTooltip;
use php\lib\arr;

/**
 * Class RegisterForm
 * @package ide\forms
 *
 * @property UXTextField $captchaField
 */
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
        list($key, $image) = Ide::service()->account()->captcha();

        $this->captcha->data('key', $key);
        $this->captcha->image = $image;
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
        $msg = new MessageBoxForm('Вы уверены, что хотите выйти из регистрации?', ['Да, выйти', 'Нет'], $this);

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

        Ide::service()->account()->registerAsync($this->emailField->text, $this->nameField->text, $this->passwordField->text, $this->captcha->data('key'), $this->captchaField->text,
            function (ServiceResponse $response) {
                if ($response->isNotSuccess()) {
                    $this->updateCaptcha();

                    if ($response->isInvalidValidation()) {
                        $errors = $response->result('errors');

                        if (arr::has($errors, 'CaptchaInvalid') || arr::has($errors, 'CaptchaNotFound')) {
                            $this->showError('Неверный код', $this->captchaField);
                        }

                        if (arr::has($errors, 'PasswordInvalid')) {
                            $this->showError('Введите корректный пароль', $this->passwordField);
                        } else if (arr::has($errors, 'PasswordRequired')) {
                            $this->showError('Введите пароль', $this->passwordField);
                        }

                        if (arr::has($errors, 'EmailRequired')) {
                            $this->showError('Введите email', $this->emailField);
                        } else if (arr::has($errors, 'EmailInvalid')) {
                            $this->showError('Введите корректный email', $this->emailField);
                        } else if (arr::has($errors, 'EmailNotUnique')) {
                            $this->showError('Аккаунт с таким email уже зарегистрирован', $this->emailField);
                        }

                        if (arr::has($errors, 'LoginRequired')) {
                            $this->showError('Введите свой псевдоним', $this->nameField);
                        } else if (arr::has($errors, 'LoginNotUnique')) {
                            $this->showError('Аккаунт с таким именем уже зарегистирован', $this->nameField);
                        }

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