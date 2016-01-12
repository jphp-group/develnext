<?php
namespace ide\forms\mixins;

use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\UXScreen;

trait SavableFormMixin
{
    /**
     * @event SavableFormMixin:show
     */
    public function doSavableFormMixinShow()
    {
        /** @var $this AbstractForm */
        $class = get_class($this);

        $config = Ide::get()->getUserConfig($class);

        $this->x = $config->get("x", $this->x);
        $this->y = $config->get("y", $this->y);
        $this->width = $config->get("width", $this->width);
        $this->height = $config->get("height", $this->height);
        $this->maximized = $config->get("maximized", $this->maximized);

        uiLater(function () use ($config) {
            /** @var $this AbstractForm */
            $this->width = $config->get("width", $this->width);
            $this->height = $config->get("height", $this->height);
        });

        $screen = UXScreen::getPrimary();

        if ($this->x > $screen->visualBounds['width'] - 20 || $this->y > $screen->visualBounds['height'] - 20) {
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

        if (!$this->maximized) {
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