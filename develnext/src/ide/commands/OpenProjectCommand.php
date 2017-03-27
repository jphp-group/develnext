<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\OpenProjectForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXMenuItem;
use php\gui\UXSplitMenuButton;

/**
 * Class OpenProjectCommand
 * @package ide\commands
 */
class OpenProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return _('menu.project.open');
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
        $button = $this->makeGlyphButton();

        $split = new UXSplitMenuButton($button->text, $button->graphic);
        $split->maxHeight = 9999;
        $split->tooltipText = $button->tooltipText;
        $split->on('action', $this->makeAction());

        $dialog = new OpenProjectForm();

        $openItem = new UXMenuItem('Открыть проект из файла');
        $openItem->on('action', [$dialog, 'doOpenButtonClick']);
        $split->items->add($openItem);

        $openUrlItem = new UXMenuItem('Открыть проект по ссылке', ico('hyperlink16'));
        //$split->items->add($openUrlItem);

        $split->items->add(UXMenuItem::createSeparator());

        $item = $this->makeMenuItem();
        $item->text = 'Все проекты';
        $split->items->add($item);

        return $split;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        static $dialog = null;

        if (!$dialog) {
            $dialog = new OpenProjectForm();
            $dialog->owner = Ide::get()->getMainForm();
        }

        $dialog->showAndWait();
    }
}