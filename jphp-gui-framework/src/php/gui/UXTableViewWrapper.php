<?php
namespace php\gui;

use php\gui\event\UXEvent;
use php\gui\layout\UXScrollPane;
use php\gui\UXData;
use php\gui\UXNodeWrapper;

class UXTableViewWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        /** @var UXTableView $node */
        $node = $this->node;

        /** @var UXTableColumn $column */
        foreach ($node->columns as $column) {
            $column->setCellValueFactoryForArrays();
        }
    }
}