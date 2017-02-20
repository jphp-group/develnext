<?php
namespace php\gui\framework;

/**
 * Class ScriptEvent
 * @package php\gui\framework
 *
 *
 * @packages framework
 */
class ScriptEvent extends \stdClass
{
    /**
     * @var AbstractScript
     **/
    public $sender;

    /**
     * @var AbstractScript|mixed
     */
    public $target;

    /**
     * @var int
     */
    public $usage = 0;

    /**
     * ScriptEvent constructor.
     * @param AbstractScript $sender
     * @param null $target
     */
    public function __construct(AbstractScript $sender = null, $target = null)
    {
        $this->sender = $sender;
        $this->target = $target ?: $sender;
    }


    public function done()
    {
        $this->usage -= 1;
    }

    public function isDone()
    {
        return $this->usage <= 0;
    }
}