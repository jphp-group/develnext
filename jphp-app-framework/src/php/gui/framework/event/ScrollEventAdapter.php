<?php
namespace php\gui\framework\event;

use php\gui\event\UXKeyEvent;
use php\gui\event\UXScrollEvent;
use php\lib\Str;
use php\util\Regex;

class ScrollEventAdapter extends AbstractEventAdapter
{
    /**
     * @param $node
     * @param callable $handler
     * @param string $param
     * @return callable
     */
    public function adapt($node, callable $handler, $param)
    {
        $param = Str::lower($param);

        switch ($param) {
            case 'started':
                return function (UXScrollEvent $event) use ($handler) {
                    if ($event->eventType == 'SCROLL_STARTED') {
                        $handler($event);
                    }
                };

            case 'finished':
                return function (UXScrollEvent $event) use ($handler) {
                    if ($event->eventType == 'SCROLL_FINISHED') {
                        $handler($event);
                    }
                };

            case 'move':
                return function (UXScrollEvent $event) use ($handler) {
                    if ($event->eventType == 'SCROLL') {
                        $handler($event);
                    }
                };

            case 'up':
                return function (UXScrollEvent $event) use ($handler) {
                    if ($event->eventType == 'SCROLL' && $event->deltaY > 0) {
                        $handler($event);
                    }
                };

            case 'down':
                return function (UXScrollEvent $event) use ($handler) {
                    if ($event->eventType == 'SCROLL' && $event->deltaY < 0) {
                        $handler($event);
                    }
                };
        }

        return null;
    }
}