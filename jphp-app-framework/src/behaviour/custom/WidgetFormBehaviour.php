<?php
namespace behaviour\custom;

use php\framework\Logger;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXPopupWindow;
use php\gui\UXScreen;
use php\gui\UXWindow;
use php\lib\reflect;

class WidgetFormBehaviour extends AbstractBehaviour
{
    /**
     * @var UXPopupWindow
     */
    protected $popup;

    /**
     * @var int
     */
    protected $offsetX = 10;

    /**
     * @var int
     */
    protected $offsetY = 10;

    /**
     * @var string
     */
    protected $position = 'BOTTOM_RIGHT';

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXWindow) {
            $popup = $this->popup = new UXPopupWindow();
            $layout = $target->layout;

            $target->x = -9999;
            $target->y = -9999;
            $target->style = 'UTILITY';
            $target->makeVirtualLayout();
            $target->size = [0, 0];

            $popup->layout = $layout;

            $target->on('showing', function () {
                $this->popup->show($this->_target, $this->_target->x, $this->_target->y);
                $this->resetPosition();
            }, __CLASS__);

            $target->observer('x')->addListener(function ($_, $new) use ($target) {
                if ($new == -9999) return;

                $this->popup->x = $new;

                uiLater(function () use ($target) {
                    $target->x = -9999;
                });
            });

            $target->observer('y')->addListener(function ($_, $new) use ($target) {
                if ($new == -9999) return;

                $this->popup->y = $new;

                uiLater(function () use ($target) {
                    $target->y = -9999;
                });
            });
        } else {
            $class = reflect::typeOf($this);
            $object = reflect::typeOf($target);
            Logger::warn("Unable to apply '$class' behaviour to '$object' object");
        }
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Reset the position of the widget to the default value.
     * --RU--
     * Сбросить позицию виджета к значению по умолчанию.
     */
    public function resetPosition()
    {
        $this->setPosition($this->position);
    }

    /**
     * Set position of the widget on screen.
     * --RU--
     * Позиция виджета относительно экрана.
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        if ($this->popup) {
            $screen = UXScreen::getPrimary();
            $x = $screen->visualBounds['x'];
            $y = $screen->visualBounds['y'];

            switch ($position) {
                case 'CENTER':
                case 'BOTTOM_CENTER':
                case 'TOP_CENTER':
                    $x = round($screen->bounds['width'] / 2 - $this->popup->width / 2);
                    break;

                case 'TOP_RIGHT':
                case 'BOTTOM_RIGHT':
                case 'MIDDLE_RIGHT':
                    $x = round($screen->bounds['width'] - $this->popup->width);
                    break;
            }

            switch ($position) {
                case 'MIDDLE_LEFT':
                case 'CENTER':
                case 'MIDDLE_RIGHT':
                    $y = round($screen->bounds['height'] / 2 - $this->popup->height / 2);
                    break;

                case 'BOTTOM_LEFT':
                case 'BOTTOM_CENTER':
                case 'BOTTOM_RIGHT':
                    $y = round($screen->bounds['height'] - $this->popup->height);
                    break;
            }

            if ($this->offsetX) {
                if ($x <= $screen->visualBounds['x']) {
                    $x += $this->offsetX;
                } else {
                    $x -= $this->offsetX;
                }
            }

            if ($this->offsetY) {
                if ($y <= $screen->visualBounds['y']) {
                    $y += $this->offsetY;
                } else {
                    $y -= $this->offsetY;
                }
            }

            $this->popup->x = $x;
            $this->popup->y = $y;

            Logger::info("Set widget position ($this->position) as ($x, $y)");
        }
    }

    /**
     * Offset by Y.
     * --RU--
     * Смещение виджета по горизонтали.
     * @return int
     */
    public function getOffsetX()
    {
        return $this->offsetX;
    }

    /**
     * @param int $offsetX
     */
    public function setOffsetX($offsetX)
    {
        $this->offsetX = $offsetX;
        $this->resetPosition();
    }

    /**
     * Offset by X.
     * --RU--
     * Смещение виджета по вертикали.
     * @return int
     */
    public function getOffsetY()
    {
        return $this->offsetY;
    }

    /**
     * @param int $offsetY
     */
    public function setOffsetY($offsetY)
    {
        $this->offsetY = $offsetY;
        $this->resetPosition();
    }

    public function getCode()
    {
        return 'widget';
    }
}