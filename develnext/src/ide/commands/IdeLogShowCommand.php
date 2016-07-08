<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\utils\FileUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\text\UXFont;
use php\gui\UXForm;
use php\gui\UXTextArea;

/**
 * Class IdeLogShowCommand
 * @package ide\commands
 */
class IdeLogShowCommand extends AbstractCommand
{
    public function getName()
    {
        return 'Диагностика среды';
    }

    public function getCategory()
    {
        return 'help';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $dialog = new UXForm();
        $dialog->title = 'IDE Logging (ide.log)';
        $dialog->style = 'UTILITY';

        $dialog->size = [1000, 600];

        $textArea = new UXTextArea(FileUtils::get(Ide::get()->getLogFile()));
        $textArea->font = new UXFont(11, 'Courier New');
        UXAnchorPane::setAnchor($textArea, 10);

        $dialog->add($textArea);

        $dialog->showAndWait();
    }

    public function getIcon()
    {
        return 'icons/diagnostic16.png';
    }

    public function isAlways()
    {
        return true;
    }


}