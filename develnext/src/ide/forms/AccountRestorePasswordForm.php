<?php
namespace ide\forms;
use ide\Ide;
use ide\account\api\ServiceResponse;
use ide\ui\Notifications;
use php\gui\UXTextField;

/**
 * Class AccountRestorePasswordForm
 * @package ide\forms
 *
 * @property UXTextField $emailField
 */
class AccountRestorePasswordForm extends AbstractOnlineIdeForm
{
    protected function init()
    {
        parent::init();

        $this->icon->image = ico('accountRestore48')->image;
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->hide();
    }

    /**
     * @event restoreButton.action
     */
    public function doRestore()
    {
        $this->showPreloader();

        Ide::service()->account()->restorePasswordAsync($this->emailField->text, function (ServiceResponse $response) {
            $this->hidePreloader();

            if ($response->isSuccess()) {
                $this->hide();

                Notifications::show('Восстановление доступа', 'Письмо с кодом восстановления отправлено на ваш email');

                uiLater(function () {
                    $dialog = new AccountRestorePasswordConfirmForm($this->emailField->text);
                    $dialog->showAndWait();
                });
            } else {
                Notifications::error('Ошибка восстановления', $response->message());

                if ($response->data() == "RestorePasswordConfirm") {
                    $this->hide();

                    uiLater(function () {
                        $dialog = new AccountRestorePasswordConfirmForm($this->emailField->text);
                        $dialog->showAndWait();
                    });
                }
            }
        });
    }
}