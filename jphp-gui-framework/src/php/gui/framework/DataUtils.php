<?php
namespace php\gui\framework;

use php\gui\layout\UXPane;
use php\gui\UXData;
use php\gui\UXNode;
use php\gui\UXParent;
use php\lang\IllegalArgumentException;
use php\lib\String;

/**
 * Class DataUtils
 * @package php\gui\framework
 */
class DataUtils
{
    private function __construct() {}

    /**
     * @param UXParent $layout
     * @param callable $callback (array $data, UXNode $node)
     */
    public static function scan(UXParent $layout, callable $callback)
    {
        foreach ($layout->childrenUnmodifiable as $node) {
            if ($node instanceof UXData) {
                $callback($node, static::getNode($layout, $node));
            } else if ($node instanceof UXParent) {
                static::scan($node, $callback);
            }
        }
    }

    /**
     * @param UXNode $node
     *
     * @param UXPane|UXParent $layout
     *
     * @return UXData
     * @throws IllegalArgumentException
     */
    public static function get(UXNode $node, UXParent $layout = null)
    {
        if (!$node->id) {
            throw new IllegalArgumentException("The node must have id value");
        }

        if (!$layout) {
            $layout = $node->parent;
        }

        if (!$layout) {
            throw new IllegalArgumentException("The node must have parent value");
        }

        $data = $layout->lookup('#data-' . $node->id);

        if (!$data) {
            $data = new UXData();
            $data->id = 'data-' . $node->id;

            $layout->add($data);

            $node->watch('id', function ($self, $prop, $old, $new) use ($data) {
                $data->id = 'data-' . $self->id;
            });
        }

        return $data;
    }

    /**
     * @param UXParent $layout
     * @param UXData $data
     *
     * @return UXNode
     */
    protected static function getNode(UXParent $layout, UXData $data)
    {
        $id = $data->id;

        if (String::startsWith($id, 'data-')) {
            return $layout->lookup('#' . String::sub($id, 5));
        }

        return null;
    }

    /**
     * @param UXNode $node
     *
     * @throws IllegalArgumentException
     */
    public static function remove(UXNode $node)
    {
        $data = static::get($node);

        $parent = $data->parent;

        if (isset($parent->children)) {
            $parent->children->remove($data);
        }
    }
}