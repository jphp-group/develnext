<?php
namespace ide\formats\form\elements;

use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXPagination;
use php\gui\UXTableCell;
use php\gui\UXTableColumn;
use php\gui\UXTableView;
use php\gui\UXTextField;

/**
 * @package ide\formats\form
 */
class TableViewFormElement extends LabeledFormElement
{
    public function getName()
    {
        return 'Таблица';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getElementClass()
    {
        return UXTableView::class;
    }

    public function getIcon()
    {
        return 'icons/table16.png';
    }

    public function getIdPattern()
    {
        return "table%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXTableView();
        $button->editable = false;

        $column = new UXTableColumn();
        $column->id = 'id';
        $column->text = 'Id';
        $column->sortable = false;
        $column->width = 50;

        $button->columns->add($column);

        $column = new UXTableColumn();
        $column->id = 'name';
        $column->text = 'Name';
        $column->sortable = false;
        $button->columns->add($column);

        $column = new UXTableColumn();
        $column->id = 'value';
        $column->text = 'Value';
        $column->sortable = false;

        $button->columns->add($column);

        return $button;
    }

    public function getDefaultSize()
    {
        return [300, 300];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXTableView;
    }
}
