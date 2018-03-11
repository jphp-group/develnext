<?php
namespace ide\action\types;

use action\Element;
use action\Score;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\NumberMethodsArgumentEditor;
use ide\editors\argument\TextMethodsArgumentEditor;
use php\lib\Str;
use php\util\Regex;

class IfVarActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'name' => 'name',
            'method' => 'textMethods',
            'value' => 'mixed',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'name' => 'Имя переменной',
            'method' => 'Метод сравнения',
            'value' => 'Значение (с чем сравнивать)',
            'not' => 'Отрицание (все наоборот)'
        ];
    }

    function isAppendSingleLevel()
    {
        return true;
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
        return 'ifVar';
    }

    function getTitle(Action $action = null)
    {
        if ($action) {
            return "Если переменная '{$action->get('name')}' ...";
        }

        return 'Если переменная ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если глобальная переменная";
        }

        $name = $action->get('name');

        if ($name[0] != '$') {
            $name = "\${$name}";
        }

        $method = TextMethodsArgumentEditor::$variants[$action->method];

        if ($action->not) {
            return Str::format("Если глобальная переменная %s НЕ `%s` -> %s ", $name, $method, $action->get('value'));
        } else {
            return Str::format("Если глобальная переменная %s `%s` -> %s ", $name, $method, $action->get('value'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifVar16.png';
    }

    function imports(Action $action = null)
    {
        return [
            Str::class,
            Regex::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $name = $action->get('name');

        if ($name[0] == '$') {
            $name = Str::sub($name, 1);
        }

        $object = "\$GLOBALS['" . $name . "']";
        $string = $action->get('value');

        $not = $action->not ? '!' : '';

        switch ($action->method) {
            case 'regex':
                return "if ({$not}Regex::match($string, $object))";

            case 'regexIgnoreCase':
                return "if ({$not}Regex::match($string, $object, Regex::CASE_INSENSITIVE))";

            case 'startsWith':
                return "if ({$not}Str::startsWith($object, $string))";

            case 'endsWith':
                return "if ({$not}Str::endsWith($object, $string))";

            case 'contains':
                return "if ({$not}Str::contains($object, $string))";

            case 'equalsIgnoreCase':
                return "if ({$not}Str::equalsIgnoreCase($object, $string))";

            case 'smaller':
                if ($not) {
                    return "if ($object >= $string)";
                } else {
                    return "if ($object < $string)";
                }

            case 'greater':
                if ($not) {
                    return "if ($object <= $string)";
                } else {
                    return "if ($object > $string)";
                }

            case 'equals':
            default:
                if ($action->not) {
                    return "if ($object != $string)";
                } else {
                    return "if ($object == $string)";
                }
        }
    }
}