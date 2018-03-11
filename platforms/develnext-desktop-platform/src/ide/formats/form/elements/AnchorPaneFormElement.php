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
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;
use php\gui\UXTitledPane;

/**
 * @package ide\formats\form
 */
class AnchorPaneFormElement extends AbstractFormElement
{
    public function getName()
    {
        return null;
    }

    public function isLayout()
    {
        return true;
    }

    public function getLayoutChildren($layout)
    {
        return $layout->children;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return null;
    }

    public function isOrigin($any)
    {
        return get_class($any) == UXAnchorPane::class;
    }
}
