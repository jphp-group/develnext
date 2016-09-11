<?php
namespace ide\doc\editors\commands;

use ide\account\api\ServiceResponse;
use ide\doc\editors\DocEditor;
use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\ui\Notifications;
use php\gui\UXDialog;
use php\gui\UXMenuItem;

class AddSubCategoryMenuCommand extends AbstractMenuCommand
{
    public function getName()
    {
        return 'Добавить подкатегорию';
    }

    public function getIcon()
    {
        return 'icons/plus16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if ($editor instanceof DocEditor) {
            $parent = $editor->getSelectedCategory();

            $name = UXDialog::input('Название подкатегории');

            if ($name !== null) {
                $editor->getDocService()->saveCategoryAsync(
                    ['name' => $name, 'parentId' => $parent ? $parent['id'] : null],
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
            $category = $editor->getSelectedCategory();
            $item->disable = !$category;
            $item->visible = $category;

            if ($category) {
                $item->text = $this->getName() . " в '{$category['name']}'";
            } else {
                $item->text = $this->getName();
            }

            if ($item->visible) {
                $item->visible = $editor->isAccessCategory();
            }
        }
    }
}