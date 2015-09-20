<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Items;
use php\lib\Str;

class SleepActionType extends AbstractSimpleActionType
{
    function attributes()
    {
        return [
            'value' => 'integer',
            'sync' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'value' => 'Интервал паузы в миллисекундах (1 сек = 1000 млсек)',
            'sync' => 'В главном потоке'
        ];
    }

    function getGroup()
    {
        return self::GROUP_SCRIPT;
    }

    function getTagName()
    {
        return "sleep";
    }

    function getTitle(Action $action = null)
    {
        return "Пауза";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Остановить выполнение действий на N миллисекунд";
        }

        if ($action->sync) {
            return Str::format("Остановить выполнение действий на %s миллисекунд в главном потоке", $action->get('value'));
        } else {
            return Str::format("Остановить выполнение действий на %s миллисекунд", $action->get('value'));
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/sleep16.png";
    }

    function isYield(Action $action)
    {
        return !$action->sync;
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        if ($action->sync) {
            return "wait({$action->get('value')})";
        } else {
            return "waitAsync({$action->get('value')},";
        }
    }
}