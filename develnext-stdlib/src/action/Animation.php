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
    static function fadeTo(UXNode $object, $duration, $value, callable $callback = null)
    {
        $anim = new UXFadeAnimation($duration, $object);
        $anim->fromValue = $object->opacity;
        $anim->toValue = $value;

        if ($callback) {
            $anim->on('finish', $callback);
        }

        $anim->play();
        return $anim;
    }

    static function fadeIn(UXNode $object, $duration, callable $callback = null)
    {
        return self::fadeTo($object, $duration, 1.0, $callback);
    }

    static function fadeOut(UXNode $object, $duration, callable $callback = null)
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