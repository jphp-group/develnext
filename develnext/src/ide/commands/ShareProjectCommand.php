<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXSeparator;

/**
 * Class SaveProjectCommand
 * @package ide\commands
 */
class ShareProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Поделиться проектом';
    }

    public function getIcon()
    {
        return 'icons/shareEx16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + Alt + S';
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function isAlways()
    {
        return true;
    }

    public function makeUiForHead()
    {
        return [new UXSeparator('VERTICAL'), $this->makeGlyphButton()];
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $project->save();

            Ide::get()->getMainForm()->toast('Проект успешно сохранен');
        }
    }
}