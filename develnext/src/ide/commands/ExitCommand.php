<?php
namespace ide\commands;

use ide\forms\MainForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\systems\ProjectSystem;
use php\lang\System;

/**
 * Class ExitCommand
 * @package ide\commands
 */
class ExitCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Выход';
    }

    public function onExecute()
    {
        ProjectSystem::close();

        Ide::get()->shutdown();
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