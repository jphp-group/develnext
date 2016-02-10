<?php
namespace ide\doc\editors\commands;

use ide\doc\editors\DocEditor;
use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use php\gui\UXMenuItem;

class EditCategoryMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Редактировать';
    }

    public function getIcon()
    {
        return 'icons/edit16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if ($editor instanceof DocEditor) {

        }
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        if ($editor instanceof DocEditor) {
            $item->disable = !$editor->getSelectedCategory();
            $item->visible = $editor->isAccessCategory();
        }
    }
}