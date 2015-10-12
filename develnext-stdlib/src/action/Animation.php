<?php
namespace action;

use php\gui\animation\UXFadeAnimation;
use php\gui\animation\UXPathAnimation;
use php\gui\framework\ScriptEvent;
use php\gui\UXNode;
use php\gui\UXWindow;
use php\lang\IllegalArgumentException;
use script\TimerScript;

class Animation
{
    static function fadeTo($object, $duration, $value, callable $callback = null)
    {
        if ($object instanceof UXNode) {
            $anim = new UXFadeAnimation($duration, $object);
            $anim->fromValue = $object->opacity;
            $anim->toValue = $value;

            if ($callback) {
                $anim->on('finish', $callback);
            }

            $anim->play();
            return $anim;
        } else {
            $diff = $value - $object->opacity;

            $steps = $duration / 25;

            $step = $diff / $steps;

            $timer = new TimerScript();
            $timer->interval = 25;
            $timer->repeatable = true;

            $timer->on('action', function (ScriptEvent $e) use ($object, $step, $value, $callback, &$steps) {
                $opacity = $object->opacity + $step;

                if ($opacity > 1) {
                    $opacity = 1;
                }

                $object->opacity = $opacity < 0 ? 0 : $opacity;

                $steps--;

                if ($steps <= 0) {
                    $e->sender->free();
                    $object->opacity = (double) $value;

                    if ($callback) {
                        $callback();
                    }
                }
            });
            $timer->start();

            return $timer;
        }
    }

    static function fadeIn($object, $duration, callable $callback = null)
    {
        return self::fadeTo($object, $duration, 1.0, $callback);
    }

    static function fadeOut($object, $duration, callable $callback = null)
    {
        return self::fadeTo($object, $duration, 0.0, $callback);
    }

    static function displace($object, $duration, $x, $y, callable $callback = null)
    {
        return self::moveTo($object, $duration, $object->x + $x, $object->y + $y, $callback);
    }

    static function moveTo($object, $duration, $x, $y, callable $callback = null)
    {
        if ($object instanceof UXNode || $object instanceof UXWindow) {
            $xOffset = $x - $object->x;
            $yOffset = $y - $object->y;

            $steps = $duration / 25;

            $xStep = $xOffset / $steps;
            $yStep = $yOffset / $steps;

            $timer = new TimerScript();
            $timer->interval = 25;
            $timer->repeatable = true;

            $timer->on('action', function (ScriptEvent $e) use ($object, $xStep, $yStep, $x, $y, $callback, &$steps) {
                $object->x += $xStep;
                $object->y += $yStep;

                $steps--;

                if ($steps <= 0) {
                    $e->sender->free();
                    $object->position = [$x, $y];

                    if ($callback) {
                        $callback();
                    }
                }
            });
            $timer->start();

            return $timer;
        }

        throw new IllegalArgumentException("Cannot animate " . $object);
    }
}