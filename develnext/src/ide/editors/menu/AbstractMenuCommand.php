<?php
namespace ide\editors\menu;

use ide\misc\AbstractCommand;

abstract class AbstractMenuCommand extends AbstractCommand
{
    public function getIcon()
    {
        return null;
    }

    public function getAccelerator()
    {
        return null;
    }

    public function isHidden()
    {
        return false;
    }

    public function withSeparator()
    {
        return false;
    }

    public function makeUiForHead()
    {
        return $this->makeGlyphButton();
    }
}