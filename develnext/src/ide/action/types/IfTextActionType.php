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
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Текст',
            'method' => 'Метод сравнения',
            'string' => 'Значение'
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

    function imports()
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

        switch ($this->method) {
            case 'regex':
                return "if (Regex::match($string, $object))";

            case 'regexIgnoreCase':
                return "if (Regex::match($string, $object, Regex::CASE_INSENSITIVE))";

            case 'startsWith':
                return "if (Str::startsWith($object, $string))";

            case 'endsWith':
                return "if (Str::endsWith($object, $string))";

            case 'contains':
                return "if (Str::contains($object, $string))";

            case 'equalsIgnoreCase':
                return "if (Str::equalsIgnoreCase($object, $string))";

            case 'equals':
            default:
                return "if ($object == $string)";
        }
    }
}