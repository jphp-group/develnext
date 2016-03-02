<?php
namespace ide\forms\mixins;

use ide\Ide;
use ide\Logger;
use php\gui\framework\AbstractForm;
use php\gui\UXScreen;

trait SavableFormMixin
{
    /**
     * @event SavableFormMixin:showing
     */
    public function doSavableFormMixinShow()
    {
        /** @var $this AbstractForm */
        $class = get_class($this);

        $config = Ide::get()->getUserConfig($class);

        if ($config->has('x') && $config->has('y')) {
            $this->x = $config->get("x", $this->x);
            $this->y = $config->get("y", $this->y);
        } else {
            $this->centerOnScreen();
            uiLater(function() {
                /** @var $this AbstractForm */
                $this->centerOnScreen();
            });
        }

        if ($config->has('width') && $config->has('height')) {
            $this->width = $config->get("width", $this->width);
            $this->height = $config->get("height", $this->height);
           // $this->layout->size = $this->size;
        }

        $this->maximized = $config->get("maximized", $this->maximized);

        Logger::debug("Init window $class, size = [$this->width, $this->height], position = [$this->x, $this->y]");

        uiLater(function () use ($config) {
            /** @var $this AbstractForm */
            $this->width = $config->get("width", $this->width);
            $this->height = $config->get("height", $this->height);
        });

        $screen = UXScreen::getPrimary();

        if ($this->height < 50) {
            uiLater(function () {
                /** @var $this AbstractForm */
                $this->height = 300;
            });
        }

        if ($this->width < 70) {
            uiLater(function () {
                /** @var $this AbstractForm */
                $this->width = 400;
            });
        }

        if ($this->x > $screen->visualBounds['width'] - 20 || $this->y > $screen->visualBounds['height'] - 20
            || $this->x < -$this->width/3 || $this->y < -$this->height/3) {
            uiLater(function () {
                /** @var $this AbstractForm */
                $this->x = $this->y = 30;
                $this->centerOnScreen();
            });
        }
    }

    /**
     * @event SavableFormMixin:hide
     */
    public function doSavableFormMixinHide()
    {
        /** @var $this AbstractForm */
        $class = get_class($this);

        $config = Ide::get()->getUserConfig($class);

        if (!$this->maximized && !$this->iconified) {
            $config->set("x", $this->x);
            $config->set("y", $this->y);
            $config->set("width", $this->width);
            $config->set("height", $this->height);
        }

        if ($this->resizable) {
            $config->set("maximized", $this->maximized);
        }
    }
}