<?php
namespace php\gui\framework;

use ide\Logger;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXData;
use php\gui\UXNode;
use php\gui\UXParent;
use php\gui\UXTabPane;
use php\gui\UXTitledPane;
use php\lang\IllegalArgumentException;
use php\lib\Str;
use php\lib\String;

/**
 * Class DataUtils
 * @package php\gui\framework
 */
class DataUtils
{
    private function __construct() {}


    public static function scanAll(UXParent $layout, callable $callback)
    {
        foreach ($layout->childrenUnmodifiable as $node) {
            if ($node instanceof UXNode) {
                $data = $node->id ? self::get($node, $layout, false) : null;
                $callback($data, $node);
            }

            if ($node instanceof UXTitledPane || $node instanceof UXScrollPane) {
                if ($node->content instanceof UXParent) {
                    self::scan($node->content, $callback);
                }
            } else if ($node instanceof UXTabPane) {
                if ($node->tabs->count) {
                    foreach ($node->tabs as $tab) {
                        if ($tab->content instanceof UXParent) {
                            self::scan($tab->content, $callback);
                        }
                    }
                }
            } else if ($node instanceof UXParent) {
                self::scan($node, $callback);
            }
        }
    }

    /**
     * @param UXParent $layout
     * @param callable $callback (array $data, UXNode $node)
     */
    public static function scan(UXParent $layout, callable $callback)
    {
        foreach ($layout->childrenUnmodifiable as $node) {
            if ($node instanceof UXData) {
                $nd = self::getNode($layout, $node);
                if ($nd) {
                    $callback($node, $nd);
                }
            } else if ($node instanceof UXTitledPane || $node instanceof UXScrollPane) {
                if ($node->content instanceof UXParent) {
                    self::scan($node->content, $callback);
                }
            } else if ($node instanceof UXTabPane) {
                if ($node->tabs->count) {
                    foreach ($node->tabs as $tab) {
                        if ($tab->content instanceof UXParent) {
                            self::scan($tab->content, $callback);
                        }
                    }
                }
            } else if ($node instanceof UXParent) {
                self::scan($node, $callback);
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
            } else if ($node instanceof UXTitledPane) {
                if ($node->content instanceof UXParent) {
                    static::cleanup($node->content);
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
        $factoryId = $node->data('-factory-id');

        if ($factoryId) {
            $d = $node->data('-factory-data');

            if ($create && !$d) {
                $d = new UXData();
                $node->data('-factory-data', $d);
            }

            return $d;
        }

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

        if (Str::startsWith($id, 'data-')) {
            return $layout->lookup('#' . Str::sub($id, 5));
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