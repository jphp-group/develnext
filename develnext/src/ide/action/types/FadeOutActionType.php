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

class FadeOutActionType extends AbstractSimpleActionType
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
            'continue' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект',
            'duration' => 'Продолжительность анимации (млсек, 1 сек = 1000 млсек)',
            'continue' => 'Не ждать окончания анимации'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
            'duration' => ['def' => 1000],
        ];
    }

    function getTagName()
    {
        return "fadeOut";
    }

    function getTitle(Action $action = null)
    {
        return "Исчезновение (анимация)";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Анимация плавного исчезновения объекта";
        }

        $object = $action->get('object');
        $duration = $action->get('duration');

        if ($action->continue) {
            return Str::format("Исчезновение объекта %s за %s млсек", $object, $duration);
        } else {
            return Str::format("Исчезновение объекта %s за %s млсек с ожиданием окончания", $object, $duration);
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/fadeOut16.png";
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
            return "Animation::fadeOut({$action->get('object')}, {$action->get('duration')})";
        } else {
            return "Animation::fadeOut({$action->get('object')}, {$action->get('duration')},";
        }
    }
}