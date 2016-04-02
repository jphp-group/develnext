<?php
namespace php\gui\framework\event;

use php\gui\event\UXMouseEvent;
use php\lib\Str;

class MousedownEventAdapter extends AbstractEventAdapter
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
            case '2x':
                return function (UXMouseEvent $event) use ($handler) {
                    if ($event->clickCount >= 2) {
                        $handler($event);
                    }
                };

            case '3x':
                return function (UXMouseEvent $event) use ($handler) {
                    if ($event->clickCount >= 3) {
                        $handler($event);
                    }
                };

            case 'left':
                return function (UXMouseEvent $event) use ($handler) {
                    if ($event->button === 'PRIMARY') {
                        $handler($event);
                    }
                };

            case 'right':
                return function (UXMouseEvent $event) use ($handler) {
                    if ($event->button === 'SECONDARY') {
                        $handler($event);
                    }
                };

            case 'middle':
                return function (UXMouseEvent $event) use ($handler) {
                    if ($event->button === 'MIDDLE') {
                        $handler($event);
                    }
                };
        }

        return null;
    }
}