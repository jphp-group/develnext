<?php
namespace php\gui\framework\behaviour\custom;
use php\gui\framework\Timer;
use script\TimerScript;

/**
 * Class AnimationBehaviour
 * @package behaviour\custom
 */
abstract class AnimationBehaviour extends AbstractBehaviour
{
    /**
     * @var int
     **/
    public $delay = 0;

    /**
     *
     * @var int
     **/
    public $duration = 1000;

    /**
     * Сколько раз повторить анимацию, -1 значит бесконечно раз
     * @var int
     **/
    public $repeatCount = -1;

    public function apply($target)
    {
        if ($this->delay > 0) {
            TimerScript::executeAfter($this->delay, function () use ($target) {
                $this->__apply($target);
            });
        } else {
            $this->__apply($target);
        }
    }

    public function __apply($target)
    {
        parent::apply($target);
    }

    /**
     * @return bool
     */
    protected function checkRepeatLimits()
    {
        static $repeats = 0;

        if ($this->repeatCount == -1) return true;

        if ($repeats >= $this->repeatCount) return false;

        $repeats += 1;

        return true;
    }
}