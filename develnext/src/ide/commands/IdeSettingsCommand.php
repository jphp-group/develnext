<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\IdeSettingsForm;
use ide\misc\AbstractCommand;
use php\gui\UXSeparator;

class IdeSettingsCommand extends AbstractCommand
{
    public function getName()
    {
        return "Общие настройки";
    }

    public function getIcon()
    {
        return 'icons/settings16.png';
    }

    public function getCategory()
    {
        return 'settings';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $form = new IdeSettingsForm();
        $form->showAndWait();
    }

    public function isAlways()
    {
        return true;
    }

    public function withAfterSeparator()
    {
        return true;
    }

    /*public function makeUiForHead()
    {
        return [new UXSeparator('VERTICAL'), $this->makeGlyphButton()];
    }*/
}