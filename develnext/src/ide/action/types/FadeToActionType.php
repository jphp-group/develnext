<?php
namespace ide\action\types;

use action\Animation;
use ide\action\AbstractActionType;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\Str;
use php\xml\DomDocument;
use php\xml\DomElement;

class FadeToActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_CONTROL;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ANIMATION;
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'duration' => 'integer',
            'value' => 'float',
            'continue' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'duration' => 'Продолжительность анимации (млсек, 1 сек = 1000 млсек)',
            'value' => 'Уровень прозрачности (от 0 до 1)',
            'continue' => 'Не ждать окончания анимации'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'duration' => ['def' => 1000],
            'value' => ['def' => 0.5],
        ];
    }

    function getTagName()
    {
        return "fadeTo";
    }

    function getTitle(Action $action = null)
    {
        return "Изменение прозрачности (анимация)";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Анимация плавного изменения прозрачности объекта";
        }

        $object = $action->get('object');
        $duration = $action->get('duration');
        $value = $action->get('value');

        if ($action->continue) {
            return Str::format("Изменение прозрачности объекта %s до %s за %s млсек", $object, $value, $duration);
        } else {
            return Str::format("Изменение прозрачности объекта %s  до %s за %s млсек с ожиданием окончания", $object, $value, $duration);
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/fadeTo16.png";
    }

    function isYield(Action $action)
    {
        return !$action->continue;
    }

    function imports(Action $action = null)
    {
        return [
            Animation::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        if ($action->continue) {
            return "Animation::fadeTo({$action->get('object')}, {$action->get('duration')}, {$action->get('value')})";
        } else {
            return "Animation::fadeTo({$action->get('object')}, {$action->get('duration')}, {$action->get('value')},";
        }
    }
}