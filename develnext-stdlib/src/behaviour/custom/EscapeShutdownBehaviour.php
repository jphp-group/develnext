<?php
namespace behaviour\custom;

use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXForm;
use php\gui\UXNode;
use php\util\SharedValue;

class EscapeShutdownBehaviour extends AbstractBehaviour
{
    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        $self = $this;

        if ($target instanceof UXNode) {
            $target->on('keyUp', function (UXKeyEvent $e) use ($self, $target) {
                if ($this->_target->isFree()) {
                    return;
                }

                if ($self->enabled && $e->codeName == 'Esc' && $target->form) {
                    $target->form->hide();
                }
            }, __CLASS__);
        } elseif ($target instanceof UXForm)  {
            $target->on('keyUp', function (UXKeyEvent $e) use ($self, $target) {
                if ($this->_target->isFree()) {
                    return;
                }

                $enabled = $self->enabled;

                if ($enabled && $e->codeName == 'Esc') {
                    $target->hide();
                }
            }, __CLASS__);
        }
    }
}