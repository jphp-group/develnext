<?php
namespace ide\formats\form\elements;

use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\designer\UXDesignPropertyEditor;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXNode;
use php\gui\UXTableCell;
use php\gui\UXTextField;

/**
 * Class ButtonFormElement
 * @package ide\formats\form
 */
class ButtonFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Кнопка';
    }

    public function getIcon()
    {
        return 'icons/button16.png';
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $button = new UXButton($this->getName());
        return $button;
    }

    public function getDefaultSize()
    {
        return [150, 35];
    }

    /**
     * @param UXDesignProperties $properties
     */
    public function createProperties(UXDesignProperties $properties)
    {
        $properties->addGroup('general', 'Главное');
        $properties->addGroup('extra', 'Дополнительно');

        $properties->addProperty('general', 'text', 'Текст', new TextPropertyEditor());
        $properties->addProperty('extra', 'x', 'Позиция X', new TextPropertyEditor());
        $properties->addProperty('extra', 'y', 'Позиция Y', new TextPropertyEditor());
    }

    public function isOrigin($any)
    {
        return $any instanceof UXButton;
    }
}
