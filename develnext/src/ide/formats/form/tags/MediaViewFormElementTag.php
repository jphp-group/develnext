<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\AbstractFormElementTag;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPanel;
use php\gui\UXDialog;
use php\gui\UXMediaView;
use php\gui\UXMediaViewBox;
use php\lib\Str;
use php\lib\String;
use php\xml\DomDocument;
use php\xml\DomElement;

class MediaViewFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'org.develnext.jphp.ext.javafx.support.control.MediaViewBox';
    }

    public function getElementClass()
    {
        return UXMediaViewBox::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXMediaViewBox $node */
        $element->setAttribute('smooth', $node->smooth ? 'true' : 'false');
        $element->setAttribute('proportional', $node->proportional ? 'true' : 'false');
    }
}