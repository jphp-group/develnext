<?php
namespace ide\doc\editors\commands;

use ide\account\api\ServiceResponse;
use ide\doc\account\api\DocService;
use ide\doc\editors\DocEditor;
use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\DocCategoryEditForm;
use ide\ui\Notifications;
use php\gui\UXMenuItem;

class EditCategoryMenuCommand extends AbstractMenuCommand
{
    /**
     * @var DocEditor
     */
    private $editor;

    /**
     * @var DocService
     */
    protected $docService;

    /**
     * EditCategoryMenuCommand constructor.
     * @param DocEditor $editor
     */
    public function __construct(DocEditor $editor = null)
    {
        $this->editor = $editor;
        $this->docService = new DocService();
    }

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
            $dialog = new DocCategoryEditForm();
            $dialog->setResult($editor->getSelectedCategory());

            $dialog->events->on('save', function ($category) {
                $this->docService->saveCategoryAsync($category, function (ServiceResponse $response) {
                    if ($response->isSuccess()) {
                        if ($this->editor) {
                            $this->editor->open();
                        }
                    } else {
                        Notifications::error('Ошибка сохранения', $response->message());
                    }
                });
            });

            $dialog->showDialog();
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