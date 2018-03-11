<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXPagination;
use php\xml\DomDocument;
use php\xml\DomElement;

class PaginationFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'PaginationEx';
    }

    public function getElementClass()
    {
        return UXPagination::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXPagination $node */

        $element->setAttribute('total', $node->total);
        $element->setAttribute('pageSize', $node->pageSize);
        $element->setAttribute('maxPageCount', $node->maxPageCount);
        $element->setAttribute('showTotal', $node->showTotal ? 'true' : 'false');
        $element->setAttribute('showPrevNext', $node->showPrevNext ? 'true' : 'false');
        $element->setAttribute('hintText', $node->hintText);
        $element->setAttribute('hgap', $node->hgap);
        $element->setAttribute('vgap', $node->vgap);
        $element->setAttribute('selectedPage', $node->selectedPage);
        $element->setAttribute('alignment', $node->alignment);

        $textColor = $node->textColor;

        if ($textColor) {
            $element->setAttribute('textColor', $textColor->getWebValue());
        }
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        parent::writeContent($node, $element, $document, $dumper);

        $this->writeFontForContent($node, $element, $document);
    }
}