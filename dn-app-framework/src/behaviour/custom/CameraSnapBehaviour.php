<?php
namespace behaviour\custom;

use php\game\UXGamePane;
use php\gui\animation\UXAnimationTimer;
use php\gui\event\UXEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\framework\View;
use php\gui\layout\UXScrollPane;
use php\gui\UXNode;
use php\lib\arr;
use php\lib\reflect;
use php\lib\str;

/**
 * Class CameraSnapBehaviour
 * @package behaviour\custom
 *
 * @packages framework
 */
class CameraSnapBehaviour extends AbstractBehaviour
{
    /**
     * @var string ALL, HORIZONTAL, VERTICAL
     */
    public $direction = 'ALL';

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXNode) {
            $offsetX = $target->x;
            $offsetY = $target->y;

            $uid = str::uuid();

            $listener = function ($oldParent, $parent) use ($uid, $target, $offsetY, $offsetX) {
                if ($oldParent) {
                    $pane = $oldParent->data('--view-pane');

                    if ($pane instanceof UXGamePane) {
                        $pane->off('scrollScene', $uid);
                    }
                }

                if ($parent) {
                    $pane = $parent->data('--view-pane');

                    if ($pane instanceof UXGamePane) {
                        $pane->on('scrollScene', function (UXEvent $e) use ($target, $offsetX, $offsetY) {
                            if ($this->enabled) {
                                if (arr::has(['ALL', 'HORIZONTAL'], $this->direction)) {
                                    $target->x = $e->sender->viewX + $offsetX;
                                }

                                if (arr::has(['ALL', 'VERTICAL'], $this->direction)) {
                                    $target->y = $e->sender->viewY + $offsetY;
                                }
                            }
                        }, $uid);
                    }
                }
            };

            if ($target->parent) {
                $listener(null, $target->parent);
            }

            $target->observer('parent')->addListener($listener);
        }
    }

    public function getCode()
    {
        return 'cameraSnap';
    }
}