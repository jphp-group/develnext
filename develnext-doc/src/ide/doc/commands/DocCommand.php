<?php
namespace ide\doc\commands;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use ide\systems\FileSystem;
use php\gui\UXSeparator;

class DocCommand extends AbstractCommand
{
    public function isAlways()
    {
        return true;
    }

    public function getName()
    {
        return 'Документация';
    }

    public function getCategory()
    {
        return 'help';
    }

    public function getIcon()
    {
        return 'icons/help16.png';
    }

    /*public function makeUiForHead()
    {
        $button = parent::makeGlyphButton();
        $button->text = 'Документация';

        return [$button, new UXSeparator('VERTICAL')];
    } */

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        FileSystem::open('~doc');
    }
}