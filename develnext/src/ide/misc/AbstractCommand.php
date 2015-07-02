<?php
namespace ide\misc;

use ide\Ide;
use php\gui\UXButton;
use php\gui\UXMenuItem;

/**
 * Class AbstractCommand
 * @package ide\misc
 */
abstract class AbstractCommand
{
    abstract public function getName();

    abstract public function onExecute();

    public function isAlways()
    {
        return false;
    }

    public function getIcon()
    {
        return null;
    }

    public function getAccelerator()
    {
        return null;
    }

    public function getCategory()
    {
        return 'project';
    }

    public function makeGlyphButton()
    {
        $button = new UXButton();
        $button->tooltipText = $this->getName();
        $button->graphic = Ide::get()->getImage($this->getIcon());
        $button->css('cursor', 'hand');
        $button->padding = [4, 5];

        $button->on('click', function () {
            $this->onExecute();
        });

        return $button;
    }

    public function makeMenuItem()
    {
        $item = new UXMenuItem($this->getName());
        $item->graphic = Ide::get()->getImage($this->getIcon());
        $item->accelerator = $this->getAccelerator();

        $item->on('action', [$this, 'onExecute']);

        return $item;
    }

    public function makeUiForHead()
    {
        return null;
    }
}