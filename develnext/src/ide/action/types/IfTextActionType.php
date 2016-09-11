<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\TextMethodsArgumentEditor;
use php\lib\Str;
use php\util\Regex;

class IfTextActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'string',
            'method' => 'textMethods',
            'string' => 'string',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Текст',
            'method' => 'Метод сравнения',
            'string' => 'Значение',
            'not' => 'Отрицание (все наоборот)'
        ];
    }

    function  attributeSettings()
    {
        return [
            'object' => ['def' => '~sender', 'defType' => 'object']
        ];
    }

    function isAppendSingleLevel()
    {
        return true;
    }

    function getGroup()
    {
        return self::GROUP_CONDITIONS;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ADDITIONAL;
    }

    function getTagName()
    {
        return 'ifText';
    }

    function getTitle(Action $action = null)
    {
        if ($action) {
            $method = TextMethodsArgumentEditor::$variants[$action->method];

            if ($method) {
                return "Если текст `$method`";
            }
        }

        return 'Если текст ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если текст";
        }

        $method = TextMethodsArgumentEditor::$variants[$action->method];

        return Str::format("Если текст %s `%s` -> %s ", $action->get('object'), $method, $action->get('string'));
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifText16.png';
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
        $object = $action->get('object');
        $string = $action->get('string');

        $not = $action->not ? '!' : '';

        switch ($this->method) {
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