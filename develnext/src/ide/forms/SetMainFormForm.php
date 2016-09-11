<?php
namespace ide\forms;

use ide\editors\common\FormListEditor;
use ide\editors\common\ObjectListEditorButtonRender;
use ide\editors\common\ObjectListEditorCellRender;
use ide\forms\mixins\DialogFormMixin;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\gui\event\UXEvent;
use php\gui\UXComboBox;
use php\gui\UXDialog;
use php\gui\UXListView;

/**
 * Class SetMainFormForm
 * @package ide\forms
 *
 * @property UXComboBox $list
 */
class SetMainFormForm extends AbstractIdeForm
{
    use DialogFormMixin;

    /**
     * @var array
     */
    protected $excludes = [];

    /**
     * @param array $names
     */
    public function setExcludedForms(array $names)
    {
        $this->excludes = $names;
    }

    protected function init()
    {
        parent::init();

        $this->list->onCellRender(new ObjectListEditorCellRender());
        $this->list->onButtonRender(new ObjectListEditorButtonRender());
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $formList = new FormListEditor();
        $formList->setEmptyItemText('[Нет]');
        $formList->build();

        $this->list->items->clear();

        foreach ($formList->getUi()->items as $item) {
            if (in_array($item->value, $this->excludes)) {
                continue;
            }

            $this->list->items->add($item);
        }

        $this->list->selectedIndex = 0;
    }

    /**
     * @event close
     * @param UXEvent $e
     */
    public function doClose(UXEvent $e)
    {
        $e->consume();
        UXDialog::show('Выберите главную форму проекта и нажмите Сохранить');
    }

    /**
     * @event saveButton.action
     */
    public function doSave()
    {
        $selected = $this->list->selected;

        $this->setResult($selected ? $selected->value : null);
        $this->hide();
    }
}