<?php
namespace action;

use php\framework\Logger;
use php\gui\animation\UXAnimationTimer;
use php\gui\animation\UXFadeAnimation;
use php\gui\animation\UXPathAnimation;
use php\gui\framework\behaviour\PositionableBehaviour;
use php\gui\framework\Instances;
use php\gui\framework\ObjectGroup;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\gui\UXWindow;
use php\lang\IllegalArgumentException;
use php\lib\reflect;
use script\TimerScript;
use timer\AccurateTimer;

class Animation
{
    static function fadeTo($object, $duration, $value, callable $callback = null)
    {
        if ($object instanceof Instances) {
            $cnt = sizeof($object);

            $done = function () use (&$cnt, $callback) {
                $cnt--;

                if ($cnt <= 0) {
                    $callback();
                }
            };

            $object->flow()->map(function () use ($object, $duration, $value, $done) {
                Animation::fadeTo($object, $duration, $value, $done);
            });
            return null;
        }

        $diff = $value - $object->opacity;

        $steps = $duration / UXAnimationTimer::FRAME_INTERVAL_MS;
        $step = $diff / $steps;

        $timer = new UXAnimationTimer(function () use ($object, $step, $value, $callback, &$steps) {
            $opacity = $object->opacity + $step;

            if ($opacity > 1) {
                $opacity = 1;
            }

            $object->opacity = $opacity < 0 ? 0 : $opacity;

            $steps--;

            if ($steps <= 0) {
                $object->opacity = (double) $value;

                if ($callback) {
                    $callback();
                }

                return true;
            }

            return false;
        });

        $timer->start();

        return $timer;
    }

    static function fadeIn($object, $duration, callable $callback = null)
    {
        return self::fadeTo($object, $duration, 1.0, $callback);
    }

    static function fadeOut($object, $duration, callable $callback = null)
    {
        return self::fadeTo($object, $duration, 0.0, $callback);
    }

    static function stopMove($object)
    {
        $timer = $object->data(Animation::class . "#moveTo");

        if ($timer instanceof UXAnimationTimer) {
            $timer->stop();
        }
    }

    static function displace($object, $duration, $x, $y, callable $callback = null)
    {
        return self::moveTo($object, $duration, $object->x + $x, $object->y + $y, $callback);
    }

    static function moveTo($object, $duration, $x, $y, callable $callback = null)
    {
        if ($object instanceof Instances) {
            $cnt = sizeof($object);

            $done = function () use (&$cnt, $callback) {
                $cnt--;

                if ($cnt <= 0) {
                    $callback();
                }
            };

            $result = [];

            $object->flow()->map(function () use ($object, $duration, $x, $y, $done, &$result) {
                $result[] = Animation::moveTo($object, $duration, $x, $y, $done);
            });

            return $result;
        }

        if ($object instanceof UXWindow) {
            if (!$object->visible) {
                if ($callback) {
                    AccurateTimer::executeAfter($duration, $callback);
                }

                return null;
            }
        }

        if ($object instanceof UXNode || $object instanceof UXWindow || $object instanceof PositionableBehaviour) {
            $xOffset = $x - $object->x;
            $yOffset = $y - $object->y;

            $steps = $duration / UXAnimationTimer::FRAME_INTERVAL_MS;

            $xStep = $xOffset / $steps;
            $yStep = $yOffset / $steps;

            $timer = new UXAnimationTimer(function () use ($object, $xStep, $yStep, $x, $y, $callback, &$steps) {
                $object->x += $xStep;
                $object->y += $yStep;

                $steps--;

                if ($steps <= 0) {
                    $object->position = [round($object->x), round($object->y)];

                    if ($callback) {
                        $object->data(Animation::class . "#moveTo", null);
                        $callback();
                    }

                    return true;
                }

                return false;
            });

            $object->data(Animation::class . "#moveTo", $timer);
            $timer->start();

            return $timer;
        }

        Logger::warn("Cannot animate object(" . reflect::typeOf($object) . "), it's not supported for this type");
    }
}