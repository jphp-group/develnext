<?php
namespace ide\forms;

use ide\account\api\ServiceResponse;
use ide\Ide;
use ide\ui\Notifications;
use php\gui\UXDialog;
use php\gui\UXPasswordField;

/**
 * Class AccountChangePasswordForm
 * @package ide\forms
 *
 * @property UXPasswordField $oldPasswordField
 * @property UXPasswordField $newPasswordField
 * @property UXPasswordField $checkPasswordField
 */
class AccountChangePasswordForm extends AbstractOnlineIdeForm
{
    protected function init()
    {
        parent::init();

        $this->icon->image = ico('flatKey48')->image;
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->hide();
    }

    /**
     * @event saveButton.action
     */
    public function doSave()
    {
        if ($this->checkPasswordField->text !== $this->newPasswordField->text) {
            UXDialog::show('Введите одинаковые пароли', 'ERROR');
            return;
        }

        $this->showPreloader();

        Ide::service()->account()->changePasswordAsync($this->oldPasswordField->text, $this->newPasswordField->text, function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                Notifications::show('Смена пароля', 'Ваш пароль был успешно изменен на новый');
                $this->hide();
            } else {
                switch ($response->message()) {
                    case 'NewPasswordRequired':
                        $message = 'Введите новый пароль';
                        $this->showError($message, $this->newPasswordField);
                        break;
                    case 'NewPasswordInvalid':
                        $message = 'Введите корректный новый пароль';
                        $this->showError($message, $this->newPasswordField);
                        break;
                    case 'OldPasswordInvalid':
                        $message = 'Старый пароль введен неверно';
                        $this->showError($message, $this->oldPasswordField);
                        break;
                    default:
                        $message = $response->isFail() ? $response->message() : 'Произошла непредвиденная ошибка, попробуйте повторить действие позже.';
                        break;
                }

                Notifications::error('Смена пароля', $message);
            }

            $this->hidePreloader();
        });
    }
}