<?php
namespace behaviour\custom;

use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXForm;
use php\gui\UXNode;
use php\util\SharedValue;

/**
 * Class EscapeShutdownBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
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

                if ($self->enabled && $e->codeName == 'Esc' && $target->window) {
                    $target->window->hide();
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

    public function getCode()
    {
        return 'escapeShutdown';
    }
}