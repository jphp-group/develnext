<?php
namespace ide\doc\editors\commands;

use ide\account\api\ServiceResponse;
use ide\doc\editors\DocEditor;
use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\MessageBoxForm;
use ide\ui\Notifications;
use php\gui\UXMenuItem;

class DeleteCategoryMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Удалить';
    }

    public function getIcon()
    {
        return 'icons/trash16.png';
    }

    public function withSeparator()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if ($editor instanceof DocEditor) {
            if ($one = $editor->getSelectedCategory()) {
                if (MessageBoxForm::confirmDelete($one['name'])) {
                    $editor->getDocService()->deleteCategoryAsync($one['id'], function (ServiceResponse $response) use ($editor) {
                        if ($response->isNotSuccess()) {
                            Notifications::error('Ошибка', $response->message());
                            return;
                        }

                        $editor->refreshTree();
                    });
                }
            }
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