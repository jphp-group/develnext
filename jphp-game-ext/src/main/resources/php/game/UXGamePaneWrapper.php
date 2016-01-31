<?php
namespace php\game;

use php\gui\UXData;
use php\gui\UXNodeWrapper;

class UXGamePaneWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        /** @var UXGamePane $node */
        $node = $this->node;

        $handle = function () use ($node) {
            $node->content->data('--view-width', $node->width);
            $node->content->data('--view-height', $node->height);
        };

        $this->node->observer('content')->addListener($handle);
        $this->node->observer('width')->addListener($handle);
        $this->node->observer('height')->addListener($handle);

        $this->node->observer('hvalue')->addListener(function ($old, $new) use ($node) {
            $node->content->data('--view-offset-x', $new);
        });

        $this->node->observer('vvalue')->addListener(function ($old, $new) use ($node) {
            $node->content->data('--view-offset-y', $new);
        });

        $handle();
    }
}