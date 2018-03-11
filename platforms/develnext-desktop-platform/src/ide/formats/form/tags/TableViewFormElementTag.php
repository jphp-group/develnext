<?php
namespace ide\formats\form\tags;

use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\UXButton;
use php\gui\UXPagination;
use php\gui\UXTableColumn;
use php\gui\UXTableView;
use php\xml\DomDocument;
use php\xml\DomElement;

class TableViewFormElementTag extends AbstractFormElementTag
{
    public function getTagName()
    {
        return 'TableView';
    }

    public function getElementClass()
    {
        return UXTableView::class;
    }

    public function writeAttributes($node, DomElement $element)
    {
        /** @var UXTableView $node */

        $element->setAttribute('editable', $node->editable ? 'true' : 'false');
        $element->setAttribute('fixedCellSize', $node->fixedCellSize);
        $element->setAttribute('tableMenuButtonVisible', $node->tableMenuButtonVisible ? 'true' : 'false');
    }

    public function writeContent($node, DomElement $element, DomDocument $document, AbstractFormDumper $dumper)
    {
        /** @var UXTableView $node */
        parent::writeContent($node, $element, $document, $dumper);

        $placeholderTag = $document->createElement('placeholder');
        $placeholderTag->appendChild($document->createElement('Label'));
        $element->appendChild($placeholderTag);

        if ($node->constrainedResizePolicy) {
            $tag = $document->createElement('columnResizePolicy');
            $tag->setAttribute('xmlns:fx', "http://javafx.com/fxml");

            $tagValue = $document->createElement('TableView');
            $tag->appendChild($tagValue);
            $tagValue->setAttribute('fx:constant', 'CONSTRAINED_RESIZE_POLICY');

            $element->appendChild($tag);
        }

        $columnsTag = $document->createElement('columns');
        $element->appendChild($columnsTag);

        /** @var UXTableColumn $column */
        foreach ($node->columns as $column) {
            $columnTag = $document->createElement('TableColumn');
            $columnTag->setAttribute('id', $column->id);
            $columnTag->setAttribute('prefWidth', $column->width);
            $columnTag->setAttribute('maxWidth', $column->maxWidth);
            $columnTag->setAttribute('minWidth', $column->minWidth);
            $columnTag->setAttribute('text', $column->text);
            $columnTag->setAttribute('style', $column->style);
            $columnTag->setAttribute('resizable', $column->resizable ? 'true' : 'false');
            $columnTag->setAttribute('sortable', 'false');
            $columnTag->setAttribute('visible', $column->visible ? 'true' : 'false');

            $columnsTag->appendChild($columnTag);
        }
    }


}