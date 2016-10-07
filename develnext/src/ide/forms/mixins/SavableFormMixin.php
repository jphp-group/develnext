<?php
namespace ide\forms\mixins;

use ide\Ide;
use ide\Logger;
use php\gui\framework\AbstractForm;
use php\gui\UXScreen;
use php\lang\IllegalArgumentException;

trait SavableFormMixin
{
    protected $lockSizes = false;

    /**
     * @return boolean
     */
    public function isLockSizes()
    {
        return $this->lockSizes;
    }

    /**
     * @param boolean $lockSizes
     */
    public function setLockSizes($lockSizes)
    {
        $this->lockSizes = $lockSizes;
    }

    /**
     * @event SavableFormMixin:show
     */
    public function doSavableFormMixinShow()
    {
        waitAsync(200, function () {
            $this->setLockSizes(false);
        });
    }

    /**
     * @event SavableFormMixin:showing
     */
    public function doSavableFormMixinShowing()
    {
        $this->lockSizes = true;

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

        if ($config->has('width') && $config->has('height') && $this->resizable) {
            $this->width = $config->get("width", $this->width);
            $this->height = $config->get("height", $this->height);

            if ($this->width < 10) {
                $this->width = 500;
            }

            if ($this->height < 10) {
                $this->height = 400;
            }
        }

        waitAsync(50, function () use ($config) {
            /** @var $this AbstractForm */
            $this->maximized = $config->get("maximized", $this->maximized);
        });

        Logger::debug("Init window $class, size = [$this->width, $this->height], position = [$this->x, $this->y]");

        if ($this->resizable) {
            if (!$this->data('--SavableFormMixin-init')) {
                $this->data('--SavableFormMixin-init', true);

                $this->observer('width')->addListener(function ($_, $new) use ($config) {
                    /** @var $this AbstractForm */
                    $w = $config->get("width", $this->width);

                    if ($new != $w && $this->isLockSizes()) {
                        $this->width = $w;
                    }
                });

                $this->observer('height')->addListener(function ($_, $new) use ($config) {
                    /** @var $this AbstractForm */
                    $h = $config->get("height", $this->height);

                    if ($new != $h && $this->isLockSizes()) {
                        $this->height = $h;
                    }
                });
            }
            //uiLater($f);
        }

        $screen = UXScreen::getPrimary();

        if ($this->height < 50) {
            $this->setLockSizes(false);

            uiLater(function () {
                /** @var $this AbstractForm */
                $this->height = 300;
            });
        }

        if ($this->width < 70) {
            $this->setLockSizes(false);

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

        $this->setLockSizes(false);
    }
}