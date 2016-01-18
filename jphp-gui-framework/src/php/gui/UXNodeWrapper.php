<?php
namespace php\gui;

use php\gui\framework\AbstractForm;

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
    }

    /**
     * @param $event
     * @param callable $handler
     * @param $group
     */
    public function bind($event, callable $handler, $group)
    {
        if (in_array($event, ['create', 'destroy'])) {
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