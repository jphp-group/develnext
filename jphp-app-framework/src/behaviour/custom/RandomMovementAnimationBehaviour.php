<?php
namespace behaviour\custom;

use action\Animation;
use php\gui\framework\behaviour\custom\AnimationBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\framework\View;
use php\gui\layout\UXRegion;
use php\gui\UXGeometry;
use php\gui\UXNode;
use php\gui\UXScreen;
use php\gui\UXWindow;
use php\util\SharedValue;
use script\TimerScript;
use timer\AccurateTimer;

class RandomMovementAnimationBehaviour extends AnimationBehaviour
{
    /**
     * @var bool
     */
    public $animated = true;

    /**
     * ANY, HORIZONTAL, VERTICAL
     * @var string
     */
    public $direction = 'ANY';

    /**
     * @var int
     */
    public $animationSpeed = 1000;

    /**
     * @var null|TimerScript
     */
    protected $_animTimer = null;

    /**
     * @var null
     */
    protected $_currentDirection = null;

    /**
     * @var SharedValue
     */
    protected $_busy;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode || $target instanceof UXWindow) {
            $this->_busy = $busy = new SharedValue(false);

            $func = function (AccurateTimer $timer) use ($target, $busy) {
                if ($target->isFree()) {
                    return;
                }

                $timer->interval = $this->duration;

                if ($this->enabled && !$busy->get()) {
                    list($x, $y) = $this->getNewRandomPosition();

                    if ($this->animated) {
                        $busy->set(true);

                        $func = function () use ($target, $busy, $x, $y) {
                            $distance = UXGeometry::distance($target->x, $target->y, $x, $y);
                            $time = ($distance / 100) * $this->animationSpeed;

                            $this->_animTimer = Animation::moveTo($target, $time, $x, $y, function () use ($busy) {
                                $busy->set(false);
                                $this->_animTimer = null;
                            });
                        };

                        if ($this->delay) {
                            waitAsync($this->delay, $func);
                        } else {
                            $func();
                        }
                    } else {
                        $busy->set(false);
                        $target->position = [$x, $y];
                    }
                }
            };

            $timer = $this->accurateTimer($this->duration, $func);
            $timer->trigger();
        }
    }

    public function getCode()
    {
        return 'randomMove';
    }

    public function disable()
    {
        parent::disable();

        if ($this->_animTimer && $this->when == 'ALWAYS') {
            $this->_animTimer->stop();
            $this->_animTimer = null;
            $this->_busy->set(false);
        }
    }

    protected function getNewRandomPosition()
    {
        $target = $this->_target;

        $paddingX = 0;
        $paddingY = 0;

        $offsetX = 0;
        $offsetY = 0;

        if ($target instanceof UXWindow) {
            $screen = UXScreen::getPrimary();
            $parentWidth  = $screen->visualBounds['width'];
            $parentHeight = $screen->visualBounds['height'];
        } else {
            $parent = $target->parent;

            $bounds = View::bounds($parent);

            $parentWidth = $bounds['width'];
            $parentHeight = $bounds['height'];
            $offsetX = $bounds['x'];
            $offsetY = $bounds['y'];

            $paddingX = $parent instanceof UXRegion ? $parent->paddingRight : 0;
            $paddingY = $parent instanceof UXRegion ? $parent->paddingBottom : 0;
        }

        switch ($this->direction) {
            case 'VERTICAL':
                $currentDirection = $this->_currentDirection;

                if (!$currentDirection) {
                    $currentDirection = $this->_currentDirection = ['UP', 'DOWN'][rand(0, 1)];
                }

                $x = rand($offsetX, $parentWidth - $target->width - $paddingX);

                switch ($currentDirection) {
                    case 'DOWN':
                        $max = $parentHeight - $target->height - $paddingY;
                        $y = rand($target->y, $max);

                        if ($y > $max - rand(0, 35)) {
                            $this->_currentDirection = 'UP';
                        }

                        break;
                    case 'UP':
                        $y = rand($offsetY, $target->y);

                        if ($y < rand(0, 35)) {
                            $this->_currentDirection = 'DOWN';
                        }

                        break;
                }
                break;

            case 'HORIZONTAL':
                $currentDirection = $this->_currentDirection;

                if (!$currentDirection) {
                    $currentDirection = $this->_currentDirection = ['LEFT', 'RIGHT'][rand(0, 1)];
                }

                $y = rand($offsetY, $parentHeight - $target->height - $paddingY);

                switch ($currentDirection) {
                    case 'RIGHT':
                        $max = $parentWidth - $target->width - $paddingX;
                        $x = rand($target->x, $max);

                        if ($x > $max - rand(0, 35)) {
                            $this->_currentDirection = 'LEFT';
                        }

                        break;
                    case 'LEFT':
                        $x = rand($offsetX, $target->x);

                        if ($x  < rand(0, 35)) {
                            $this->_currentDirection = 'RIGHT';
                        }

                        break;
                }
                break;

            default:
                $x = rand($offsetX, $parentWidth - $target->width - $paddingX);
                $y = rand($offsetY, $parentHeight - $target->height - $paddingY);
                break;
        }

        return [$x, $y];
    }
}