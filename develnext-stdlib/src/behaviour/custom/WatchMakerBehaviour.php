<?php
namespace behaviour\custom;

use action\Element;
use php\gui\event\UXKeyEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\framework\Timer;
use php\gui\UXForm;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;
use php\time\Time;
use php\time\TimeZone;
use php\util\SharedValue;
use script\TimerScript;

class WatchMakerBehaviour extends AbstractBehaviour
{
    /**
     * @var string
     */
    public $format = 'HH:mm';

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        $updater = function (ScriptEvent $e = null) use ($target) {
            $now = Time::now();

            try {
                Element::setText($target, $now->toString($this->format));
            } catch (IllegalArgumentException $e) {
                Element::setText($target, "[invalid format]");
            }
        };

        $updater();
        $this->timer(50, $updater);
    }

    public function getCode()
    {
        return 'watchMaker';
    }
}