<?php
namespace php\gui\framework\behaviour\custom;
use php\gui\animation\UXAnimationTimer;


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
     * @var string
     */
    public $when = 'ALWAYS';

    /**
     * Сколько раз повторить анимацию, -1 значит бесконечно раз
     * @var int
     **/
    public $repeatCount = -1;

    /**
     * @var UXAnimationTimer[]
     */
    protected $__animTimers = [];

    public function apply($target)
    {
        if ($this->delay > 0 && $this->when == 'ALWAYS') {
            TimerScript::executeAfter($this->delay, function () use ($target) {
                $this->__apply($target);
            });
        } else {
            $types = $this->getWhenEventTypes();

            if ($types) {
                $this->enabled = false;

                $target->on($types[0], function () {
                    $this->enable();
                }, get_class($this));

                $target->on($types[1], function () {
                    $this->disable();
                    $this->restore();
                }, get_class($this));
            }

            $this->__apply($target);
        }
    }

    public function __apply($target)
    {
        parent::apply($target);
    }

    protected function getWhenEventTypes()
    {
        switch ($this->when) {
            case 'HOVER':
                return ['mouseEnter', 'mouseExit'];
            case 'CLICK':
                return ['mouseDown', 'mouseUp'];
        }

        return null;
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

    protected function animTimer(callable $func)
    {
        $this->__animTimers[] = $timer = new UXAnimationTimer($func);
        $timer->start();
        return $timer;
    }

    public function free()
    {
        parent::free();

        foreach ($this->__animTimers as $timer) {
            $timer->stop();
        }

        $this->__animTimers = [];
    }

    protected function restore()
    {
        ;
    }
}