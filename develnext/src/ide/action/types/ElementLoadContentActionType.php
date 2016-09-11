<?php
namespace ide\action\types;

use action\Element;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\io\Stream;
use php\lib\Items;
use php\lib\Str;

class ElementLoadContentActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'object' => 'object',
            'path' => 'string',
            'sync' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'path' => 'Источник (файл, url, и т.д.)',
            'sync' => 'В главном потоке'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
        ];
    }

    function getGroup()
    {
        return self::GROUP_CONTROL;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMPONENT;
    }

    function getTagName()
    {
        return "elementLoadContent";
    }

    function getTitle(Action $action = null)
    {
        return "Загрузить контент";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Загрузить контент в объект из источника (файла, url и т.д.)";
        }

        if ($action->sync) {
            return Str::format("Загрузить контент в объект %s из %s в главном потоке", $action->get('object'), $action->get('path'));
        } else {
            return Str::format("Загрузить контент в объект %s из %s", $action->get('object'), $action->get('path'));
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/download16.png";
    }

    function isYield(Action $action)
    {
        return !$action->sync && $action->getFieldType('object') != 'variable';
    }

    function imports(Action $action = null)
    {
        return [
            Element::class,
            Stream::class,
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        if ($action->sync) {
            switch ($action->getFieldType('object')) {
                case 'variable':
                    $actionScript->addLocalVariable($action->get('object'));
                    return "{$action->get('object')} = Stream::getContents({$action->get('path')})";
                default:
                    return "Element::loadContent({$action->get('object')}, {$action->get('path')})";
            }
        } else {

            switch ($action->getFieldType('object')) {
                case 'variable':
                    $actionScript->addLocalVariable($action->get('object'));
                    return "{$action->get('object')} = Stream::getContents({$action->get('path')})";
                default:
                    return "Element::loadContentAsync({$action->get('object')}, {$action->get('path')},";
            }
        }
    }
}