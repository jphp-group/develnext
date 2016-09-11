<?php
namespace ide\doc\editors\commands;

use ide\account\api\ServiceResponse;
use ide\doc\editors\DocEditor;
use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\ui\Notifications;
use php\gui\UXDialog;
use php\gui\UXMenuItem;

class AddCategoryMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Добавить категорию';
    }

    public function getIcon()
    {
        return 'icons/plus16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if ($editor instanceof DocEditor) {
            $name = UXDialog::input('Название категории');

            if ($name !== null) {
                $editor->getDocService()->saveCategoryAsync(
                    ['name' => $name],
                    function (ServiceResponse $response) use ($editor) {
                        if ($response->isNotSuccess()) {
                            Notifications::error('Ошибка', $response->message());
                            return;
                        }

                        $editor->refreshTree();
                    }
                );
            }
        }
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        if ($editor instanceof DocEditor) {
            $item->visible = $editor->isAccessCategory();
        }
    }
}