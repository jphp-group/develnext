<?php
namespace ide\commands;

use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXSeparator;

/**
 * Class SaveProjectCommand
 * @package ide\commands
 */
class SaveProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Сохранить';
    }

    public function getIcon()
    {
        return 'icons/save16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + S';
    }

    public function isAlways()
    {
        return true;
    }

    public function makeUiForHead()
    {
        return [$this->makeGlyphButton()];
    }

    public function onExecute()
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $project->save();

            Ide::get()->getMainForm()->toast('Проект успешно сохранен');
        }
    }
}