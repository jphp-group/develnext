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

class IfScoreActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'name' => 'string',
            'method' => 'numberMethods',
            'value' => 'integer',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'name' => 'Счет',
            'method' => 'Метод сравнения',
            'value' => 'Значение (с чем сравнивать)',
            'not' => 'Отрицание (все наоборот)'
        ];
    }

    function  attributeSettings()
    {
        return [
            'name' => ['def' => 'global']
        ];
    }

    function isAppendSingleLevel()
    {
        return true;
    }

    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ADDITIONAL;
    }

    function getTagName()
    {
        return 'ifScore';
    }

    function getTitle(Action $action = null)
    {
        if ($action) {
            return "Если счет {$action->get('name')} ...";
        }

        return 'Если счет ...';
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если счет";
        }

        $method = NumberMethodsArgumentEditor::$variants[$action->method];

        return Str::format("Если счет %s `%s` -> %s ", $action->get('name'), $method, $action->get('value'));
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifScore16.png';
    }

    function imports(Action $action = null)
    {
        return [
            Score::class,
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

        $not = (bool) $action->not;

        $score = "Score::get($name)";
        $value = $action->get('value');

        switch ($action->method) {
            case 'equals':
                if ($not) {
                    return "if ($score != $value)";
                } else {
                    return "if ($score == $value)";
                }

            case 'smaller':
                if ($not) {
                    return "if ($score >= $value)";
                } else {
                    return "if ($score < $value)";
                }

            case 'greater':
                if ($not) {
                    return "if ($score <= $value)";
                } else {
                    return "if ($score > $value)";
                }

            case 'mod':
                if ($not) {
                    return "if ($score % $value != 0)";
                } else {
                    return "if ($score % $value == 0)";
                }
        }

        return "";
    }
}