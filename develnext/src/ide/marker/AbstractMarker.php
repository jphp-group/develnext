<?php
namespace ide\marker;
use ide\misc\EventHandlerBehaviour;
use php\gui\framework\ScriptEvent;
use php\gui\event\UXEvent;

/**
 * Class AbstractMarker
 * @package ide\marker
 */
abstract class AbstractMarker
{
    use EventHandlerBehaviour;

    /**
     * @var mixed
     */
    protected $target;

    /**
     * @var mixed
     */
    protected $context;

    /**
     * AbstractMarker constructor.
     * @param mixed $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    public function linkTo($context)
    {
        $this->context = $context;

        $event = UXEvent::makeMock($this);
        $this->trigger('link', [$event, $context]);
    }

    abstract protected function showImpl();
    abstract protected function hideImpl();

    public function show()
    {
        $event = UXEvent::makeMock($this);
        $this->trigger('show', [$event]);

        if ($event->isConsumed()) {
            return;
        }

        $this->showImpl();
    }

    public function hide()
    {
        $event = UXEvent::makeMock($this);
        $this->trigger('hide', [$event]);

        if ($event->isConsumed()) {
            return;
        }

        $this->hideImpl();
    }
}