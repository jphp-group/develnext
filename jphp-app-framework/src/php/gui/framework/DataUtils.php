<?php
namespace php\gui\framework;

use ide\Logger;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXApplication;
use php\gui\UXData;
use php\gui\UXNode;
use php\gui\UXParent;
use php\gui\UXTabPane;
use php\gui\UXTitledPane;
use php\lang\IllegalArgumentException;
use php\lib\Str;

/**
 * Class DataUtils
 * @package php\gui\framework
 *
 * @packages framework
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
        $nodes = self::_cleanup($parent);

        foreach ($nodes as $node) {
            $node->free();
        }
    }

    /**
     * @param UXParent $parent
     * @param array $exists
     * @return UXData[]
     */
    private static function _cleanup(UXParent $parent, array &$exists = [])
    {
        if (UXApplication::isUiThread()) {
            $result = [];

            foreach ($parent->childrenUnmodifiable as $node) {
                if ($node instanceof UXData) {
                    $id = $node->id;

                    if ($exists[$id]) {
                        $result[] = $node;
                        continue;
                    }

                    $nd = static::getNode($parent, $node);

                    if (!$nd || $nd->parent !== $node->parent) {
                        $result[] = $node;
                    } else {
                        $exists[$id] = true;
                    }
                } else if ($node instanceof UXTitledPane) {
                    if ($node->content instanceof UXParent) {
                        $result += static::_cleanup($node->content, $exists);
                    }
                } else if ($node instanceof UXScrollPane) {
                    if ($node->content instanceof UXParent) {
                        $result += static::_cleanup($node->content, $exists);
                    }
                } else if ($node instanceof UXTabPane) {
                    foreach ($node->tabs as $tab) {
                        if ($tab->content instanceof UXParent) {
                            $result += static::_cleanup($tab->content, $exists);
                        }
                    }
                } else if ($node instanceof UXParent) {
                    $result += static::_cleanup($node, $exists);
                }
            }

            return $result;
        } else {
            Logger::warn("Unable to cleanup() in non-ui thread.");
            return [];
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

        if ($data) {
            $data->free();
        }
    }
}