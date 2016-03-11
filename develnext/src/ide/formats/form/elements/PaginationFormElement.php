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
use php\gui\UXTextField;

/**
 * @package ide\formats\form
 */
class PaginationFormElement extends LabeledFormElement
{
    public function getName()
    {
        return 'Пагинация';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getElementClass()
    {
        return UXPagination::class;
    }

    public function getIcon()
    {
        return 'icons/paginator16.png';
    }

    public function getIdPattern()
    {
        return "pagination%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXPagination();
        $button->alignment = 'CENTER';
        $button->total = 1000;
        $button->maxPageCount = 7;
        $button->showPrevNext = false;
        $button->hgap = $button->vgap = 5;

        return $button;
    }

    public function getDefaultSize()
    {
        return [350, 35];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXPagination;
    }
}
