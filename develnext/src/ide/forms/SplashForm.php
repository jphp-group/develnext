<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use php\gui\framework\AbstractForm;
use php\gui\UXApplication;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\gui\framework\Timer;

/**
 * @property UXLabel $version
 */
class SplashForm extends AbstractIdeForm
{
    protected function init()
    {
        Logger::info("Init form ...");

        $this->centerOnScreen();

        $this->version->text = $this->_app->getVersion();

        Timer::run(7000, function() {
            if ($this->_app->getMainForm()->visible) {
                $this->hide();
            }
        });
    }

    /**
     * @event click
     */
    public function hide()
    {
        parent::hide();
    }
}