<?php
namespace ide\editors\menu;

use ide\editors\AbstractEditor;

abstract class AbstractMenuCommand
{
    abstract public function getName();
    abstract public function onExecute($e, AbstractEditor $editor);

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
}