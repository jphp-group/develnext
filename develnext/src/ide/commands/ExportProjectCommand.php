<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXDialog;
use php\gui\UXFileChooser;
use php\gui\UXSeparator;
use php\io\File;
use php\lib\Str;

/**
 * Class ExportProjectCommand
 * @package ide\commands
 */
class ExportProjectCommand extends AbstractProjectCommand
{
    public function getName()
    {
        return _('menu.project.save.as.archive');
    }

    public function getIcon()
    {
        return 'icons/saveAs16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + Shift + S';
    }

    public function makeUiForHead()
    {
        return [$this->makeGlyphButton(), new UXSeparator('VERTICAL')];
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function isAlways()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $dialog = new UXFileChooser();
            $dialog->initialFileName = $project->getName() . ".zip";
            $dialog->extensionFilters = [['extensions' => ['*.zip'], 'description' => 'Zip Архив с проектом']];

            if ($file = $dialog->showSaveDialog()) {
                if (!Str::endsWith($file, '.zip')) {
                    $file .= '.zip';
                }

                $project->export($file);

                Ide::toast(_('toast.project.save.zip.done'));
            }
        } else {
            UXDialog::show(_('alert.project.export.fail'));
        }
    }
}