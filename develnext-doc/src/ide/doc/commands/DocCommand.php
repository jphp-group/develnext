<?php
namespace ide\doc\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\systems\FileSystem;
use ide\ui\Notifications;
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

    public function makeUiForRightHead()
    {
        $button = $this->makeGlyphButton();
        $button->text = $this->getName();

        return $button;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if (Ide::get()->isDevelopment()) {
            FileSystem::open('~doc');
        } else {
            Notifications::show('В разработке', 'Данная функция находится в разработке...', 'INFORMATION');
        }
    }
}