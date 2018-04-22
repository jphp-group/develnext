<?php
namespace php\gui\framework\event;

use php\gui\event\UXKeyEvent;
use php\lib\Str;
use php\util\Regex;

class KeydownEventAdapter extends AbstractEventAdapter
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
            case 'printscreen':
                $param = 'Print Screen';
                break;

            case 'previoustrack':
                $param = 'Previous Track';
                break;

            case 'nexttrack':
                $param = 'Next Track';
                break;

            case 'volumeup':
                $param = 'Volume Up';
                break;

            case 'volumedown':
                $param = 'Volume Down';
                break;
        }

        switch ($param) {
            case 'anydigit':
                return function (UXKeyEvent $event) use ($param, $handler) {
                    if (Str::contains('0123456789', $event->codeName) && str::length($event->codeName) == 1) {
                        $handler($event);
                    }
                };

            case 'anyletter':
                return function (UXKeyEvent $event) use ($param, $handler) {
                    if (Str::contains('qwertyuiopasdfghjklzxcvbnm', Str::lowerFirst($event->codeName)[0]) && str::length($event->codeName) == 1) {
                        $handler($event);
                    }
                };

            case 'anydirection':
                return function (UXKeyEvent $event) use ($param, $handler) {
                    switch ($event->codeName) {
                        case 'Left': case 'Right':case 'Top':case 'Bottom':
                            $handler($event);
                            break;
                    }
                };

            case 'anyf':
                return function (UXKeyEvent $event) use ($param, $handler) {
                    if (Regex::match('^F[0-9]{1,2}$', $event->codeName)) {
                        $handler($event);
                    }
                };

            default:
                if (Str::contains($param, '+')) {
                    list($mod, $param) = Str::split($param, '+');

                    $handler = $this->adapt($node, $handler, $param);

                    switch ($mod) {
                        case 'alt':
                            return function (UXKeyEvent $event) use ($param, $handler) {
                                if ($event->altDown) {
                                    $handler($event);
                                }
                            };

                        case 'ctrl':
                            return function (UXKeyEvent $event) use ($param, $handler) {
                                if ($event->controlDown) {
                                    $handler($event);
                                }
                            };

                        case 'shift':
                            return function (UXKeyEvent $event) use ($param, $handler) {
                                if ($event->shiftDown) {
                                    $handler($event);
                                }
                            };

                        default:
                            return null;
                    }
                } else {
                    return function (UXKeyEvent $event) use ($param, $handler) {
                        if (Str::equalsIgnoreCase($param, $event->codeName)) {
                            $handler($event);
                        }
                    };
                }
        }
    }
}