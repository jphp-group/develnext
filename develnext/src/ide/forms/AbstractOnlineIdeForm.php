<?php
namespace ide\forms;

use action\Animation;
use ide\account\ui\NeedAuthPane;
use ide\Ide;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\gui\UXNode;
use php\gui\UXTooltip;

class AbstractOnlineIdeForm extends AbstractIdeForm
{
    /**
     * @var UXNode
     */
    protected $originalLayout;

    /**
     * @return bool
     */
    public function isAuthRequired()
    {
        return false;
    }


    public function showError($errorText, UXNode $node)
    {
        $tooltip = new UXTooltip();
        $tooltip->classes->add('dn-tooltip-error');
        $tooltip->text = $errorText;

        $tooltip->showByNode($node, 0, $node->height + 2);

        waitAsync(3000, function () use ($tooltip) { $tooltip->hide(); });
    }

    /**
     * AbstractOnlineIdeForm constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->originalLayout = $this->layout;

        $this->on('show', function () {
            $this->updateUi();

            Ide::accountManager()->on('login', [$this, 'updateUi'], get_class($this));
            Ide::accountManager()->on('logout', [$this, 'updateUi'], get_class($this));

            if (!Ide::service()->canPrivate()) {
                Notifications::showAccountUnavailable();

                UXApplication::runLater(function () {
                    $this->hide();
                });
            }
        }, get_class($this));
    }

    public function updateUi()
    {
        if (Ide::accountManager()->isAuthorized() || !$this->isAuthRequired()) {
            $this->layout = $this->originalLayout;
        } else {
            $this->layout = new NeedAuthPane();
        }
    }
}