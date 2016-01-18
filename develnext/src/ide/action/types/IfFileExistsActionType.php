<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\io\File;
use php\lib\Str;

class IfFileExistsActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'file' => 'string',
            'not' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'file' => 'Путь к файлу',
            'not' => 'Отрицание (наоборт, если не существует)'
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
        return 'ifFileExists';
    }

    function getTitle(Action $action = null)
    {
        if (!$action || !$action->not) {
            return 'Если есть файл ...';
        } else {
            return 'Если нет файла ...';
        }
    }

    function getDescription(Action $action = null)
    {
        if ($action == null) {
            return "Если существует файл";
        }

        if ($action->not) {
            return Str::format("Если НЕ существует файл %s", $action->get('file'));
        } else {
            return Str::format("Если существует файл %s", $action->get('file'));
        }
    }

    function getIcon(Action $action = null)
    {
        return 'icons/ifFile16.png';
    }

    function imports(Action $action = null)
    {
        return [
            File::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $file = $action->get('file');

        if ($action->not) {
            return "if (!File::of({$file})->isFile())";
        } else {
            return "if (File::of({$file})->isFile())";
        }
    }
}