<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;

class LaunchNewIDECommand extends AbstractCommand
{
    public function getName()
    {
        return "Новая IDE";
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        Ide::get()->startNew();
    }

    public function isAlways()
    {
        return true;
    }
}