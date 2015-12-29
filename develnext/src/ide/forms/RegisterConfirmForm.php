<?php
namespace ide\forms;

use ide\account\api\ServiceResponse;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\ui\Notifications;
use php\gui\framework\AbstractForm;
use php\gui\UXDialog;

class RegisterConfirmForm extends AbstractOnlineIdeForm
{
    use DialogFormMixin;

    protected function init()
    {
        parent::init();

        $this->icon->image = Ide::get()->getImage('DevelNextIco.png')->image;
    }

    public function setEmail($email)
    {
        $this->emailField->text = $email;
        $this->emailField->enabled = !$email;
    }

    /**
     * @event okButton.action
     */
    public function actionDone()
    {
        $this->showPreloader();

        Ide::service()->account()->confirmAsync($this->emailField->text, $this->keyField->text,
            function (ServiceResponse $response) {
                if ($response->isNotSuccess()) {
                    Notifications::showInvalidValidation();

                    $this->hidePreloader();
                    $this->setResult(false);
                    return;
                }

                UXDialog::show($response->message());
                $this->hidePreloader();

                $this->setResult($response->data());
                $this->hide();
            }
        );
    }
}