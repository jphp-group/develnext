<?php
namespace ide\forms;
use ide\Ide;
use ide\account\api\ServiceResponse;
use ide\ui\Notifications;
use php\gui\UXTextField;

/**
 * @package ide\forms
 *
 * @property UXTextField $emailField
 * @property UXTextField $confirmKeyField
 */
class AccountRestorePasswordConfirmForm extends AbstractOnlineIdeForm
{
    /**
     * AccountRestorePasswordConfirmForm constructor.
     * @param $email
     */
    public function __construct($email)
    {
        parent::__construct();

        $this->emailField->text = $email;
    }

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
        if (MessageBoxForm::confirmExit()) {
            $this->hide();
        }
    }

    /**
     * @event confirmButton.action
     */
    public function doConfirm()
    {
        $this->showPreloader();

        Ide::service()->account()->restorePasswordConfirmAsync($this->emailField->text, $this->confirmKeyField->text, function (ServiceResponse $response) {
            $this->hidePreloader();

            if ($response->isSuccess()) {
                $this->hide();

                uiLater(function () use ($response) {
                    Notifications::show('Доступ восстановлен', 'Доступ к аккаунту был успешно восстановлен, мы поменяли вам пароль.');
                    $dialog = new ShowTextDialogForm('Ваш новый пароль для входа', $response->data());
                    $dialog->showDialog();
                });
            } else {
                Notifications::error('Ошибка восстановления', $response->message());
            }
        });
    }
}