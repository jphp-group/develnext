<?php
namespace ide\commands;

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

    public function onExecute()
    {
        $dialog = new OpenProjectForm();
        $dialog->showDialog();
    }
}