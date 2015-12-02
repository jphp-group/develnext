<?php
namespace ide\editors\argument;

use ide\editors\common\ObjectListEditorButtonRender;
use ide\editors\common\ObjectListEditorItem;
use php\gui\UXComboBox;
use php\gui\UXListCell;
use php\gui\UXNode;

/**
 * Class EnumArgumentEditor
 * @package ide\editors\argument
 */
class EnumArgumentEditor extends AbstractArgumentEditor
{
    /**
     * @var UXComboBox
     */
    protected $list;

    /**
     * @var bool
     */
    protected $noneItem = '...';

    /**
     * @return string
     */
    public function getCode()
    {
        return 'enum';
    }

    public function disableNoneItem()
    {
        $this->noneItem = false;
    }

    /**
     * @param null $label
     * @return UXNode
     */
    public function makeUi($label = null)
    {
        $this->list = new UXComboBox();

        $this->list->visibleRowCount = 30;

        if ($this->noneItem) {
            $this->list->items->add(new ObjectListEditorItem($this->noneItem, null, ''));
        }

        $this->list->items->addAll($this->options);

        $callback = function (UXListCell $cell, $item) {
            if ($item instanceof ObjectListEditorItem) {
                $cell->text = $item->text;
                $cell->graphic = $item->graphic;
                $cell->padding = 3;
                $cell->paddingLeft = 3 + $item->level * 10;
            } else {
                $cell->text = "$item";
                $cell->graphic = null;
            }
        };
        $this->list->maxWidth = 9999;

        $this->list->onCellRender($callback);
        $this->list->onButtonRender(new ObjectListEditorButtonRender());
        //$this->list->onButtonRender($callback);

        $this->list->on('action', function () {
            $value = $this->list->selected;

            if ($value instanceof ObjectListEditorItem) {
                $value = $value->value;
            } else {
                $value = "$value";
            }

            $this->value = $value;
            $this->valueType = 'string';
        });

        return $this->list;
    }

    public function requestUiFocus()
    {
        $this->list->requestFocus();
    }

    public function setValue($value, $type)
    {
        parent::setValue($value, $type);

        $this->setSelected($value);
    }

    private function setSelected($value)
    {
        $this->list->selectedIndex = 0;

        /** @var ObjectListEditorItem $item */
        foreach ($this->list->items as $i => $item) {
            if ($i > 0 && (($item instanceof ObjectListEditorItem && $item->value === $value) || ("$item" == "$value") )) {
                $this->list->selectedIndex = $i;
                break;
            }
        }
    }

    public function getValue()
    {
        $selected = $this->list->selected;
        return $selected instanceof ObjectListEditorItem ? $selected->value : "$selected";
    }
}