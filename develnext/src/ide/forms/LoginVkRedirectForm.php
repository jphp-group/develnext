<?php
namespace ide\forms;

use ide\account\api\AccountService;
use ide\account\api\ServiceResponse;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use php\gui\framework\ScriptEvent;
use php\gui\UXDesktop;
use php\gui\framework\AbstractForm;
use php\gui\framework\Timer;
use php\gui\UXButton;
use php\gui\UXClipboard;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXTextField;
use script\TimerScript;

/**
 * Class LoginVkRedirectForm
 * @package ide\forms
 *
 * @property UXHyperlink $urlLink
 * @property UXButton $copyButton
 * @property UXTextField $confirmField
 */
class LoginVkRedirectForm extends AbstractForm
{
    use DialogFormMixin;

    public function setAuthUrl($url)
    {
        $this->urlLink->text = $url;
    }

    /**
     * @event show
     */
    public function actionShow()
    {
        $timer = new TimerScript();
        $timer->interval = 1000;
        $timer->repeatable = true;

        $i = 3;

        $this->timerLabel->text = "Через $i сек";

        $timer->on('action', function (ScriptEvent $e) use (&$i) {
            $this->timerLabel->text = "Через " . (--$i) . " сек";

            if ($i <= 0) {
               $e->sender->stop();
            }
        });

        $timer->start();

        Timer::run($i * 1000, function () {
            $desktop = new UXDesktop();
            $desktop->browse($this->urlLink->text);
        });
    }

    /**
     * @event copyButton.action
     */
    public function actionCopy()
    {
        UXClipboard::setText($this->urlLink->text);
    }

    /**
     * @event urlLink.click
     */
    public function actionRedirect()
    {
        $desktop = new UXDesktop();
        $desktop->browse($this->urlLink->text);
    }

    /**
     * @event loginVkButton.action
     */
    public function actionLoginVkButton()
    {
        $this->alwaysOnTop = false;

        Ide::service()->account()->authExternalAsync($this->confirmField->text, function (ServiceResponse $response) {
            if ($response->isSuccess()) {
                UXDialog::show($response->message());

                $this->setResult($response->data());
                $this->hide();
            } else {
                UXDialog::show($response->message(), 'ERROR');
            }
        });
    }
}