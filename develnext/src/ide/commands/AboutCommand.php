<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use php\gui\UXDesktop;

class AboutCommand extends AbstractCommand
{
    public function getName()
    {
        return 'О проекте';
    }

    public function isAlways()
    {
        return true;
    }

    public function getCategory()
    {
        return 'help';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        app()->showFormAndWait('SplashForm');
    }
}