<?php
namespace php\gui;

use php\gui\event\UXEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\View;

class UXNodeWrapper
{
    /**
     * @var UXNode
     */
    protected $node;

    /**
     * UXNodeWrapper constructor.
     *
     * @param $node
     */
    public function __construct(UXNode $node)
    {
        $this->node = $node;
    }

    /**
     * @param UXData $data
     */
    public function applyData(UXData $data)
    {
        if ($data->has('classesString')) {
            $this->node->classesString .= $data->get('classesString');
        }

        if ($data->has('enabled')) {
            $this->node->enabled = $data->get('enabled');
        }

        if ($data->has('visible')) {
            $this->node->visible = $data->get('visible');
        }

        if ($data->has('tooltipText') && $this->node instanceof UXControl) {
            $this->node->tooltipText = $data->get('tooltipText');
        }

        if ($data->has('cursor') && $data->get('cursor') !== 'DEFAULT') {
            $this->node->cursor = $data->get('cursor');
        }

        $this->node->data('--start-position', $this->node->position);
    }

    /**
     * @param $event
     * @param callable $handler
     * @param $group
     */
    public function bind($event, callable $handler, $group)
    {
        switch ($event) {
            case 'create':
                return;

            case 'destroy':
                $this->node->observer('parent')->addListener(function ($old, $new) use ($handler) {
                    if (!$new) {
                        $handler(UXEvent::makeMock($this->node));
                    }
                });

                return;

            case 'outside-partly':
                $listener = function () use ($handler) {
                    $handle = function () use ($handler) {
                        $handler(UXEvent::makeMock($this->node));
                    };

                    if ($parent = $this->node->parent) {
                        $x = $this->node->x;
                        $y = $this->node->y;

                        $bounds = View::bounds($parent);

                        if ($x < $bounds['x']) {
                            $handle();
                        } elseif ($y < $bounds['y']) {
                            $handle();
                        } elseif ($x > $bounds['width'] - $this->node->width) {
                            $handle();
                        } elseif ($y > $bounds['height'] - $this->node->height) {
                            $handle();
                        }
                    }
                };

                $this->node->observer('layoutX')->addListener($listener);
                $this->node->observer('layoutY')->addListener($listener);
                return;

            case 'outside':
                $listener = function () use ($handler) {
                    $handle = function () use ($handler) {
                        $handler(UXEvent::makeMock($this->node));
                    };

                    if ($parent = $this->node->parent) {
                        $x = $this->node->x;
                        $y = $this->node->y;

                        $bounds = View::bounds($parent);

                        if ($x + $this->node->width < $bounds['x']) {
                            $handle();
                        } elseif ($y + $this->node->height < $bounds['y']) {
                            $handle();
                        } elseif ($x > $bounds['width']) {
                            $handle();
                        } elseif ($y > $bounds['height']) {
                            $handle();
                        }
                    }
                };

                $this->node->observer('layoutX')->addListener($listener);
                $this->node->observer('layoutY')->addListener($listener);
                return;
        }

        $this->node->on($event, $handler, $group);
    }

    /**
     * @param AbstractForm|UXNode $node
     * @return AbstractFormWrapper|UXNodeWrapper
     */
    static function get($node)
    {
        $wrapper = $node->data('~wrapper');

        if ($wrapper) {
            return $wrapper;
        }

        if ($node instanceof AbstractForm) {
            $wrapper = new AbstractFormWrapper($node);
        } else {
            $class = get_class($node) . 'Wrapper';

            if (class_exists($class)) {
                $wrapper = new $class($node);
            } else {
                $wrapper = new UXNodeWrapper($node);
            }
        }

        $node->data('~wrapper', $wrapper);

        return $wrapper;
    }
}