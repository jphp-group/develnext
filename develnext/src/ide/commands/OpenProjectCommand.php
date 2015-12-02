<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\OpenProjectForm;
use ide\misc\AbstractCommand;

/**
 * Class OpenProjectCommand
 * @package ide\commands
 */
class OpenProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Открыть проект';
    }

    public function getIcon()
    {
        return 'icons/open16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + Alt + O';
    }

    public function isAlways()
    {
        return true;
    }

    public function makeUiForHead()
    {
        return $this->makeGlyphButton();
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $dialog = new OpenProjectForm();
        $dialog->showDialog();
    }
}