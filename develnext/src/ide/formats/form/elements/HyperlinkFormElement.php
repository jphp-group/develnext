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
use php\gui\UXHyperlink;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;

/**
 * Class HyperlinkFormElement
 * @package ide\formats\form
 */
class HyperlinkFormElement extends LabeledFormElement
{
    public function getName()
    {
        return 'Ссылка';
    }

    public function getElementClass()
    {
        return UXHyperlink::class;
    }

    public function getIcon()
    {
        return 'icons/hyperlink16.png';
    }

    public function getIdPattern()
    {
        return "link%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXHyperlink($this->getName());
        return $button;
    }

    public function getDefaultSize()
    {
        return [150, 20];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXHyperlink;
    }
}
