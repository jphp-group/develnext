<?php
namespace ide\misc;

trait EventHandlerBehaviour
{
    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var bool
     */
    private $handleLock = false;


    public function trigger($event, array $args = [])
    {
        if ($this->handleLock) {
            return null;
        }

        $result = null;

        foreach ((array) $this->handlers[$event] as $handler) {
            $result = $handler(...$args);

            if ($result) {
                return $result;
            }
        }

        return $result;
    }

    public function isLockHandles()
    {
        return $this->handleLock;
    }

    public function lockHandles()
    {
        $this->handleLock = true;
    }

    public function unlockHandles()
    {
        $this->handleLock = false;
    }

    public function on($event, callable $handler, $group = 'general')
    {
        $this->handlers[$event][$group] = $handler;
    }

    public function off($event, $group = null)
    {
        if ($group === null) {
            unset($this->handlers[$event]);
        } else {
            unset($this->handlers[$event][$group]);
        }
    }
}