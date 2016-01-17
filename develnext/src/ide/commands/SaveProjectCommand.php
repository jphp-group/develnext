<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\marker\ArrowPointMarker;
use ide\marker\target\CurrentEditorMarkerTarget;
use ide\misc\AbstractCommand;
use php\gui\UXSeparator;

/**
 * Class SaveProjectCommand
 * @package ide\commands
 */
class SaveProjectCommand extends AbstractProjectCommand
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

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $project->save();

            /*$marker = new ArrowPointMarker(new CurrentEditorMarkerTarget());
            $marker->tooltipText = "Нажмите на это поле, \nчтобы добавить выделенный компонент!";
            $marker->show();*/

            Ide::get()->getMainForm()->toast('Проект успешно сохранен');
        }
    }
}