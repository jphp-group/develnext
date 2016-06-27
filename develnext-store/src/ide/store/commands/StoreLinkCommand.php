<?php
namespace ide\store\commands;

use ide\doc\editors\DocEditor;
use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\systems\FileSystem;
use ide\ui\Notifications;
use php\gui\event\UXKeyEvent;
use php\gui\layout\UXHBox;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXSeparator;
use php\gui\UXTextField;

class StoreLinkCommand extends AbstractCommand
{
    public function isAlways()
    {
        return true;
    }

    public function getName()
    {
        return 'Магазин';
    }

    public function getIcon()
    {
        return 'icons/cart16.png';
    }

    public function getPriority()
    {
        return parent::getPriority() * 10;
    }

    public function makeUiForRightHead()
    {
        $button = $this->makeGlyphButton();
        $button->text = $this->getName();
        $button->maxHeight = 999;
        $button->classes->add('flat-button');
        $button->textColor = 'blue';
        $button->padding = [0, 15];

        //UXHBox::setMargin($button, [0, 0, 0, 10]);

        $ui = new UXHBox([$button]);
        $ui->spacing = 5;
        $ui->fillHeight = true;

        return $ui;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        FileSystem::openOrRefresh('~doc');
    }
}