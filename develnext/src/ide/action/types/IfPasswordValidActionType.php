<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\io\File;
use php\lib\Str;

class IfPasswordValidActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'input' => 'string',
            'password' => 'string',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'input' => 'Источник ввода пароля',
            'password' => 'Оригинальный пароль (хеш sha1, если не строка)',
            'not' => 'Отрицание (если пароль неверный)',
        ];
    }

    function attributeSettings()
    {
        return [
            'input' => ['def' => '~sender', 'defType' => 'object']
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
        return 'ifPasswordValidExists';
    }

    function getHelpText()
    {
        return 'Для проверки паролей используется хеширование sha1 (необратимое шифрование),' .
           ' даже если исходники вашей программы будут открыты, оригинальный пароль никто не узнает!';
    }

    function getTitle(Action $action = null)
    {
        if (!$action || !$action->not) {
            return 'Если пароль верный ...';
        } else {
            return 'Если пароль неверный ...';
        }
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если пароль верный";
        }

        if ($action->not) {
            return Str::format("Если пароль из %s неверный (%s)", $action->get('input'), $action->get('password'));
        } else {
            return Str::format("Если пароль из %s верный (%s)", $action->get('input'), $action->get('password'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifPasswordValid16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $input = $action->get('input');

        $salt = Str::random(4);
        $password = $action->get('password');

        $not = $action->not ? '!' : '=';

        switch ($action->getFieldType('password')) {
            case 'string':
                $password = sha1($action->password . '#' . $salt);
                return "if (sha1($input . '#$salt') $not= '$password')";

            default:
                return "if (sha1($input) $not= $password)";
        }
    }
}