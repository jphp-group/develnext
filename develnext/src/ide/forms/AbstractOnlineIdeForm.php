<?php
namespace ide\forms;

use action\Animation;
use ide\Ide;
use ide\ui\Notifications;
use php\gui\UXApplication;

class AbstractOnlineIdeForm extends AbstractIdeForm
{
    /**
     * AbstractOnlineIdeForm constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->on('show', function () {
            if (!Ide::service()->canPrivate()) {
                Notifications::showAccountUnavailable();

                UXApplication::runLater(function () {
                    $this->hide();
                });
            }
        }, __CLASS__);
    }
}