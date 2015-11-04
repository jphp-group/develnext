<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;

class SetVarActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'name' => 'name',
            'value' => 'mixed',
        ];
    }

    function attributeLabels()
    {
        return [
            'name' => 'Имя переменной',
            'value' => 'Значение',
        ];
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_DATA;
    }

    function getTagName()
    {
        return "setVar";
    }

    function getTitle(Action $action = null)
    {
        return "Задать глобальную переменную";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Задать значение глобальной переменной";
        }

        $name = $action->get('name');

        if ($name[0] != '$') {
            $name = "\${$name}";
        }

        return Str::format("Переменная %s = %s", $name, $action->get('value'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/point16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $name = $action->get('name');

        $actionScript->addLocalVariable($name);

        if ($name[0] == '$') {
            $name = Str::sub($name, 1);
        }

        return "\$GLOBALS['{$name}'] = {$action->get('value')}";
    }
}