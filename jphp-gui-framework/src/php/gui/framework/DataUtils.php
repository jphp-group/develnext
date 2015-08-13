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
                $nd = static::getNode($layout, $node);
                if ($nd) {
                    $callback($node, $nd);
                }
            } else if ($node instanceof UXParent) {
                static::scan($node, $callback);
            }
        }
    }

    /**
     * @param UXParent $parent
     */
    public static function cleanup(UXParent $parent)
    {
        foreach ($parent->childrenUnmodifiable as $node) {
            if ($node instanceof UXData) {
                $nd = static::getNode($parent, $node);

                if (!$nd) {
                    $parent->children->remove($node);
                }
            } else if ($node instanceof UXParent) {
                static::cleanup($node);
            }
        }
    }

    /**
     * @param UXNode $node
     *
     * @param UXPane|UXParent $layout
     *
     * @param bool $create
     * @return UXData
     * @throws IllegalArgumentException
     */
    public static function get(UXNode $node, UXParent $layout = null, $create = true)
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

        if (!$data && $create) {
            $data = new UXData();
            $data->id = 'data-' . $node->id;

            $layout->add($data);
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