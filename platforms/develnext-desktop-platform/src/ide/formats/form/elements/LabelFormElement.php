<?php
namespace ide\formats\form\elements;

use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormElement;
use php\gui\designer\UXDesignProperties;
use php\gui\UXLabel;
use php\gui\UXLabelEx;
use php\gui\UXNode;

class LabelFormElement extends LabeledFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Текст';
    }

    public function getElementClass()
    {
        return UXLabel::class;
    }

    public function getIcon()
    {
        return 'icons/label16.png';
    }

    public function getIdPattern()
    {
        return "label%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXLabelEx($this->getName());
        return $element;
    }

    public function getDefaultSize()
    {
        return [100, 20];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXLabel || $any instanceof UXLabelEx;
    }

    public function registerNode(UXNode $node)
    {
        /** @var UXLabel $node */
        if (get_class($node) === UXLabel::class) {
            $new = new UXLabelEx();
            $new->id = $node->id;
            $new->text = $node->text;
            $new->textColor = $node->textColor;
            $new->backgroundColor = $node->backgroundColor;
            $new->anchors = $node->anchors;
            $new->font = $node->font;
            $new->alignment = $node->alignment;
            $new->textAlignment = $node->textAlignment;

            $new->size = $node->size;
            $new->position = $node->position;
            $new->style = $node->style;
            $new->rotate = $node->rotate;
            $new->opacity = $node->opacity;

            $node->parent->children->add($new);

            $node->free();

            $node = $new;
        }

        parent::registerNode($node);
    }
}