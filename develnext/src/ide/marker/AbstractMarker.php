<?php
namespace ide\marker;
use ide\marker\target\AbstractMarkerTarget;
use ide\marker\target\MarkerTargable;
use ide\misc\EventHandlerBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\event\UXEvent;
use php\gui\UXNode;
use script\TimerScript;
use timer\AccurateTimer;

/**
 * Class AbstractMarker
 * @package ide\marker
 */
abstract class AbstractMarker
{
    use EventHandlerBehaviour;

    /**
     * @var int
     */
    public $timeout = 5000;

    /**
     * @var MarkerTargable
     */
    protected $target;

    /**
     * @var mixed
     */
    protected $context;

    /**
     * @var bool
     */
    protected $showing = false;

    /**
     * @var UXNode
     */
    protected $targetNode;

    /**
     * AbstractMarker constructor.
     * @param MarkerTargable $target
     */
    public function __construct(MarkerTargable $target)
    {
        $this->target = $target;
    }

    public function linkTo($context)
    {
        $this->context = $context;

        $event = UXEvent::makeMock($this);
        $this->trigger('link', [$event, $context]);
    }

    abstract protected function showImpl(UXNode $node);
    abstract protected function hideImpl(UXNode $node);

    public function show()
    {
        if ($this->showing) {
            $this->hide();
        }

        $this->targetNode = $node = $this->target->getMarkerNode();

        if ($node instanceof UXNode) {
            $this->showing = true;

            $event = UXEvent::makeMock($this);
            $this->trigger('show', [$event]);

            if ($event->isConsumed()) {
                return;
            }

            $this->showImpl($node);

            if ($this->timeout) {
                AccurateTimer::executeAfter($this->timeout, [$this, 'hide']);
            }
        }
    }

    public function hide()
    {
        if ($this->targetNode) {
            $event = UXEvent::makeMock($this);
            $this->trigger('hide', [$event]);

            if ($event->isConsumed()) {
                return;
            }

            $this->hideImpl($this->targetNode);
        }

        $this->showing = false;
    }
}