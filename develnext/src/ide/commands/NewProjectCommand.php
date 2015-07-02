<?php
namespace ide\commands;

use ide\forms\NewProjectForm;
use ide\misc\AbstractCommand;

/**
 * Class NewProjectCommand
 * @package ide\commands
 */
class NewProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Новый проект';
    }

    public function getIcon()
    {
        return 'icons/new16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + Alt + N';
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
        $dialog = new NewProjectForm();

        $dialog->showDialog();
    }
}