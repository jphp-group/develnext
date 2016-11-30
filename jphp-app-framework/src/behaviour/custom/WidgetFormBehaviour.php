<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXDialog;
use php\gui\UXNode;
use php\gui\UXPopupWindow;
use php\gui\UXWindow;
use php\util\SharedValue;
use script\TimerScript;

class WidgetFormBehaviour extends AbstractBehaviour
{
    /**
     * @var UXPopupWindow
     */
    protected $popup;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXWindow) {
            $popup = $this->popup = new UXPopupWindow();
            $layout = $target->layout;

            $target->x = -1000;
            $target->y = -1000;
            $target->style = 'UTILITY';
            $target->makeVirtualLayout();

            $popup->layout = $layout;

            $target->on('showing', function () {
                $this->popup->show($this->_target, $this->_target->x, $this->_target->y);
            }, __CLASS__);
        } else {

        }
    }

    public function getCode()
    {
        return 'widget';
    }
}