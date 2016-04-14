<?php
namespace behaviour\custom;

use php\game\UXGamePane;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\View;
use php\gui\layout\UXScrollPane;
use php\gui\UXNode;

class CameraTargetBehaviour extends AbstractBehaviour
{
    /**
     * @var string CENTER, RELATIVE
     */
    public $position = 'CENTER';

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
                $offsetX = $target->x;
                $offsetY = $target->y;

                $listener = function () use ($target, $offsetX, $offsetY) {
                    if (!$this->enabled) {
                        return;
                    }

                    if (($parent = $target->parent) && ($pane = $parent->data('--view-pane'))) {
                        if ($pane instanceof UXGamePane) {
                            $x = $target->x - $target->width / 2;
                            $y = $target->y - $target->height / 2;

                            switch ($this->position) {
                                case 'RELATIVE':
                                    $pane->scrollTo($x - $offsetX, $y - $offsetY);
                                    break;

                                case 'CENTER':
                                default:
                                    $y = $y - $pane->width / 2;
                                    $x = $x - $pane->height / 2;

                                    $pane->scrollTo($x, $y);

                                    break;
                            }
                        } else if ($pane instanceof UXScrollPane) {
                            $pane->scrollToNode($target);
                        }
                    }
                };

                $target->observer('layoutX')->addListener($listener);
                $target->observer('layoutY')->addListener($listener);
        }
    }

    public function getCode()
    {
        return 'cameraTarget';
    }


}