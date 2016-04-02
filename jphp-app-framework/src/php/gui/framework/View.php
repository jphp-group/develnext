<?php
namespace php\gui\framework;

use php\gui\UXNode;

class View
{
    /**
     * @param UXNode $node
     * @return array
     */
    static function bounds(UXNode $node)
    {
        $offsetX = $node->data('--view-offset-x') ?: 0;
        $offsetY = $node->data('--view-offset-y') ?: 0;
        $viewWidth = $node->data('--view-width') ?: $node->width;
        $viewHeight = $node->data('--view-height') ?: $node->height;

        return [
            'x' => $offsetX,
            'y' => $offsetY,
            'width' => $viewWidth,
            'height' => $viewHeight,
        ];
    }
}