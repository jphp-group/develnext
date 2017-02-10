<?php
namespace php\gui\framework;

use php\game\UXGamePane;
use php\gui\layout\UXScrollPane;
use php\gui\UXNode;

/**
 * Class View
 * @package php\gui\framework
 *
 * @packages framework
 */
class View
{
    /**
     * @param UXNode $node
     * @return array
     */
    static function bounds(UXNode $node)
    {
        if ($node instanceof UXGamePane) {
            $offsetX = $node->viewX;
            $offsetY = $node->viewY;
        } elseif ($node instanceof UXScrollPane) {
            $viewportBounds = $node->viewportBounds;

            $offsetX = -$viewportBounds['x'];
            $offsetY = -$viewportBounds['y'];
        } else {
            $offsetX = $node->data('--view-offset-x') ?: 0;
            $offsetY = $node->data('--view-offset-y') ?: 0;
        }

        if ($viewPane = $node->data('--view-pane')) {
            list($viewWidth, $viewHeight) = $viewPane->size;

            if ($viewPane instanceof UXGamePane) {
                $offsetX = $viewPane->viewX;
                $offsetY = $viewPane->viewY;
            }
        } else {
            $viewWidth = $node->data('--view-width') ?: $node->width;
            $viewHeight = $node->data('--view-height') ?: $node->height;
        }

        return [
            'x' => $offsetX,
            'y' => $offsetY,
            'width' => $viewWidth,
            'height' => $viewHeight,
        ];
    }
}