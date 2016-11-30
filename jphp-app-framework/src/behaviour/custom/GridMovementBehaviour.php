<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\event\UXKeyboardManager;
use php\gui\event\UXKeyEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXForm;
use php\gui\UXNode;
use php\gui\UXWindow;
use script\TimerScript;

class GridMovementBehaviour extends AbstractBehaviour
{
    /**
     * @var bool
     */
    public $animated = true;

    /**
     * @var int
     */
    public $tileWidth = 32;

    /**
     * @var int
     */
    public $tileHeight = 32;

    /**
     * @var bool
     */
    public $changeAngle = false;

    /**
     * @var int
     */
    public $speed = 100;

    /**
     * @var null|TimerScript
     */
    protected $timer = null;

    /**
     * @var bool
     */
    public $wasd = false;

    /**
     * @var string ALL, LEFT_RIGHT, UP_DOWN
     */
    public $direction = 'ALL';

    /**
     * @var bool
     */
    protected $__busy = false;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        TimerScript::executeWhile(function () {
            return $this->findForm();
        }, function () use ($target) {
            $form = $this->findForm();

            if (!$form) {
                return;
            }

            $keyboardManager = new UXKeyboardManager($form);

            $pressHandler = function (UXKeyEvent $e) use ($target) {
                if ($this->_target->isFree()) {
                    return;
                }

                if (!$this->enabled) {
                    return;
                }

                if ($this->__busy) {
                    return;
                }

                $x = $y = 0;

                list($up, $down, $left, $right) = ['Up', 'Down', 'Left', 'Right'];

                if ($this->wasd) {
                    list($up, $down, $left, $right) = ['W', 'S', 'A', 'D'];
                }

                switch ($e->codeName) {
                    case $up:
                        if (in_array($this->direction, ['ALL', 'UP_DOWN'])) {
                            $y = -$this->tileHeight;

                            if ($this->changeAngle) {
                                $target->rotate = 90;
                            }
                        }

                        break;

                    case $down:
                        if (in_array($this->direction, ['ALL', 'UP_DOWN'])) {
                            $y = $this->tileHeight;

                            if ($this->changeAngle) {
                                $target->rotate = 270;
                            }
                        }

                        break;

                    case $right:
                        if (in_array($this->direction, ['ALL', 'LEFT_RIGHT'])) {
                            $x = $this->tileWidth;

                            if ($this->changeAngle) {
                                $target->rotate = 0;
                            }
                        }

                        break;

                    case $left:
                        if (in_array($this->direction, ['ALL', 'LEFT_RIGHT'])) {
                            $x = -$this->tileWidth;

                            if ($this->changeAngle) {
                                $target->rotate = 180;
                            }
                        }

                        break;

                    default:
                        return;
                }

                $this->__busy = true;

                if ($this->animated && ($x != 0 || $y != 0)) {
                    $this->timer = Animation::displace($target, $this->speed, $x, $y, function () {
                        $this->__busy = false;
                    });
                } else {
                    $this->__busy = false;
                    $target->x += $x;
                    $target->y += $y;
                }
            };

            foreach (['Up', 'Down', 'Left', 'Right', 'W', 'S', 'A', 'D'] as $key) {
                $keyboardManager->onPress($key, $pressHandler, __CLASS__);
            }
        });
    }

    protected function findForm()
    {
        $target = $this->_target;

        $form = null;

        if ($target instanceof UXNode) {
            $form = $target->window;
        } elseif ($target instanceof UXWindow) {
            $form = $target;
        }

        return $form;
    }

    public function getCode()
    {
        return 'gridMove';
    }
}