<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\BuildSuccessForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\systems\ProjectSystem;
use php\gui\UXDialog;
use php\gui\UXFileChooser;
use php\gui\UXSeparator;
use php\io\File;
use php\lib\Str;

/**
 * Class ExportProjectCommand
 * @package ide\commands
 */
class ImportProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Загрузить из архива';
    }

    public function getIcon()
    {
        return 'icons/boxAdd16.png';
    }

    public function isAlways()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $dialog = new UXFileChooser();
        $dialog->extensionFilters = [['extensions' => ['*.zip'], 'description' => 'Zip Архив с проектом']];

        if ($file = $dialog->showOpenDialog()) {
            ProjectSystem::import($file);
        }
    }
}