<?php
namespace ide\editors\value;

use ide\editors\common\ObjectListEditorButtonRender;
use ide\editors\common\ObjectListEditorCellRender;
use ide\editors\common\ObjectListEditorItem;
use ide\editors\FormEditor;
use ide\Ide;
use ide\systems\FileSystem;
use php\gui\UXComboBox;
use php\gui\UXListCell;
use php\gui\UXNode;
use php\xml\DomElement;

/**
 * Class FormObjectPropertyEditor
 * @package ide\editors\value
 */
class FormObjectPropertyEditor extends ElementPropertyEditor
{
    /**
     * @var UXComboBox
     */
    protected $combobox;

    /**
     * @return string
     */
    public function getCode()
    {
        return "formObject";
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $combobox = new UXComboBox();
        $combobox->padding = 3;
        $combobox->visibleRowCount = 30;
        $combobox->maxWidth = 300;
        $this->combobox = $combobox;

        $combobox->on('action', function () use ($combobox) {
            $this->applyValue($combobox->value ? $combobox->value->value : null, false);
        });

        $combobox->onCellRender(new ObjectListEditorCellRender());
        $combobox->onButtonRender(new ObjectListEditorButtonRender());

        return $combobox;
    }

    public function updateUi($value, $noRefreshDesign = false)
    {
        parent::updateUi($value, $noRefreshDesign);

        $editor = FileSystem::getSelectedEditor();

        $this->combobox->items->clear();
        $objects = [ new ObjectListEditorItem('[нет]', null, '') ];

        $this->combobox->items->addAll($objects);

        if ($editor instanceof FormEditor) {
            $objects = flow($objects)->append($editor->getObjectList());

            $this->combobox->items->setAll($objects);

            foreach ($objects as $i => $one) {
                if ($one->value == $value) {
                    $this->combobox->selectedIndex = $i;
                    return;
                }
            }
        }

        $this->combobox->selectedIndex = 0;
    }

    /**
     * @param DomElement $element
     *
     * @return ElementPropertyEditor
     */
    public function unserialize(DomElement $element)
    {
        $editor = new static();
        return $editor;
    }
}