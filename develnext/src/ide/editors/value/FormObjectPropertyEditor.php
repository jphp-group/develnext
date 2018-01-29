<?php
namespace ide\editors\value;

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
        $this->combobox = $combobox;

        $combobox->onButtonRender(function (UXListCell $cell, ?ObjectListEditorItem $item) {
            if ($item) {
                $cell->text = $item->value;
                $cell->graphic = Ide::getImage($item->graphic);
            } else {
                $cell->text = null;
                $cell->graphic = null;
            }
        });

        $combobox->onCellRender(function (UXListCell $cell, ?ObjectListEditorItem $item) {
            if ($item) {
                $cell->text = $item->value;
                $cell->graphic = Ide::getImage($item->graphic);
            } else {
                $cell->text = null;
                $cell->graphic = null;
            }
        });

        return $combobox;
    }

    public function updateUi($value, $noRefreshDesign = false)
    {
        parent::updateUi($value, $noRefreshDesign);

        $editor = FileSystem::getSelectedEditor();

        if ($editor instanceof FormEditor) {
            $objects = $editor->getObjectList();

            $this->combobox->items->setAll($objects);

            foreach ($objects as $one) {
                if ($one->value == $value) {
                    $this->combobox->value = $value;
                    break;
                }
            }
        }
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