<?php
namespace ide\commands;

use ide\misc\AbstractCommand;
use ide\systems\ProjectSystem;

/**
 * Class CloseProjectCommand
 * @package ide\commands
 */
class CloseProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Закрыть проект';
    }

    public function onExecute()
    {
        ProjectSystem::close();
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function isAlways()
    {
        return true;
    }
}