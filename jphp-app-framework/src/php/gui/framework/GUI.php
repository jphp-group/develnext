<?php
namespace php\gui\framework;

use php\gui\UXCheckbox;
use php\gui\UXComboBoxBase;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXTextInputControl;
use php\lib\Str;
use Traversable;

/**
 * Class GUI
 * @package php\gui\framework
 *
 * @packages framework
 */
class GUI
{
    /**
     * @param Traversable|array $nodes
     * @param string $prefix
     *
     * @return array
     */
    static function getValues($nodes, $prefix = '')
    {
        $result = [];

        foreach ($nodes as $node) {
            $id = $node->id;

            if ($id && Str::startsWith($id, $prefix)) {
                $name = Str::sub($id, Str::length($prefix));
                $value = GUI::getValue($node);

                $result[$name] = $value;
            }
        }

        return $result;
    }

    /**
     * @param Traversable|array $nodes
     * @param array $values
     * @param string $prefix
     */
    static function setValues($nodes, array $values, $prefix = '')
    {
        foreach ($nodes as $node) {
            $id = $node->id;

            if ($id && Str::startsWith($id, $prefix)) {
                $name = Str::sub($id, Str::length($prefix));
                $value = $values[$name];
                GUI::setValue($node, $value);
            }
        }
    }

    /**
     * @param UXNode $node
     *
     * @return bool|int[]|mixed|null|string
     */
    static function getValue(UXNode $node)
    {
        if ($node instanceof UXTextInputControl) {
            return $node->text;
        }

        if ($node instanceof UXCheckbox) {
            return $node->selected;
        }

        if ($node instanceof UXListView) {
            return $node->selectedIndexes;
        }

        if ($node instanceof UXComboBoxBase) {
            return $node->value;
        }

        return null;
    }

    /**
     * @param UXNode $node
     * @param $value
     */
    static function setValue(UXNode $node, $value)
    {
        if ($node instanceof UXTextInputControl) {
            $node->text = $value;
        } else if ($node instanceof UXCheckbox) {
            $node->selected = (bool) $value;
        } else if ($node instanceof UXListView) {
            $node->selectedIndexes = (array) $value;
        } else if ($node instanceof UXComboBoxBase) {
            $node->value = $value;
        }
    }
}