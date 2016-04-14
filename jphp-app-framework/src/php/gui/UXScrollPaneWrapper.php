<?php
namespace php\gui;

use php\gui\layout\UXScrollPane;
use php\gui\UXData;
use php\gui\UXNodeWrapper;

class UXScrollPaneWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        /** @var UXScrollPane $node */
        $node = $this->node;

        $handle = function () use ($node) {
            $bounds = $node->boundsInParent;
            $node->content->data('--view-width', $bounds['width']);
            $node->content->data('--view-height', $bounds['height']);
            $node->content->data('--scroll-pane', $node);
        };

        $this->node->observer('content')->addListener($handle);
        $this->node->observer('width')->addListener($handle);
        $this->node->observer('height')->addListener($handle);

        $this->node->observer('hvalue')->addListener(function ($old, $new) use ($node) {
            $node->content->data('--view-offset-x', -$node->viewportBounds['x']);
        });

        $this->node->observer('vvalue')->addListener(function ($old, $new) use ($node) {
            $node->content->data('--view-offset-y', -$node->viewportBounds['y']);
        });

        $handle();
    }
}