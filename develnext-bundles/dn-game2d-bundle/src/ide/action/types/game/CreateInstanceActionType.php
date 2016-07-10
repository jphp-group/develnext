<?php
namespace ide\action\types\game;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\MixedArgumentEditor;
use ide\editors\argument\ObjectArgumentEditor;
use ide\editors\common\ObjectListEditor;
use ide\editors\common\ObjectListEditorItem;
use ide\formats\form\elements\FormFormElement;
use ide\formats\form\elements\GamePaneFormElement;
use ide\formats\form\elements\PanelFormElement;
use php\lib\str;

class CreateInstanceActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMMON;
    }

    function attributes()
    {
        return [
            'id' => 'prototype',
            'x' => 'integer',
            'y' => 'integer',
            'parent' => 'object',
            'relative' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'id' => 'Объект (прототип)',
            'x' => 'X (координата)',
            'y' => 'Y (координата)',
            'parent' => 'Относительно какого объекта',
            'relative' => 'Относительно',
        ];
    }

    function attributeSettings()
    {
        return [
            'id' => ['def' => '~sender'],
            'x' => ['def' => '0'],
            'y' => ['def' => '0'],
        ];
    }

    function getTagName()
    {
        return "createInstance";
    }

    function getTitle(Action $action = null)
    {
        return str::format("Создать %s", $action ? $action->get('id') : 'клона');
    }

    function getDescription(Action $action = null)
    {
        if ($action) {
            $parent = $action->get('parent');

            if ($parent) {
                return str::format(
                    "Создать клона от объекта %s, относительно = %s, [x, y] = [%s, %s], относительно объекта %s",
                    $action->get('id'), $action->relative ? 'да' : 'нет', $action->get('x'), $action->get('y'), $parent
                );
            } else {
                return str::format(
                    "Создать клона от объекта %s, относительно = %s, [x, y] = [%s, %s]",
                    $action->get('id'), $action->relative ? 'да' : 'нет', $action->get('x'), $action->get('y')
                );
            }
        } else {
            return "Создать клона от объекта";
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/idea16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $x = $action->get('x');
        $y = $action->get('y');
        $parent = $action->parent ? $action->get('parent') : ($action->relative ? '$event->sender' : 'null');

        $actionScript->addLocalVariable('instance');

        if (!$action->relative) {
            if ($x == 0 && $y == 0) {
                return "\$instance = \$this->create({$action->get('id')}, $parent)";
            } else {
                return "\$instance = \$this->create({$action->get('id')}, $parent, $x, $y)";
            }
        } else {
            if ($x == 0 && $y == 0) {
                return "\$instance = \$this->create({$action->get('id')}, $parent)";
            } else {
                return "\$instance = \$this->create({$action->get('id')}, $parent, $x, $y)";
            }
        }
    }
}