<?php
namespace php\gui;

use php\gui\event\UXEvent;
use php\gui\layout\UXScrollPane;
use php\gui\UXData;
use php\gui\UXNodeWrapper;

class UXPaginationWrapper extends UXNodeWrapper
{
    public function bind($event, callable $handler, $group)
    {
        /** @var UXPagination $node */
        $node = $this->node;

        switch ($event) {
            case 'action':
                $node->observer('selectedPage')->addListener(function () use ($handler, $node) {
                    uiLater(function () use ($handler, $node) {
                        $handler(UXEvent::makeMock($node));
                    });
                });

                return;

            default:
                parent::bind($event, $handler, $group);
                return;
        }

    }
}