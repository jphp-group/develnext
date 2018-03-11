<?php
namespace ide\action\types;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\xml\DomDocument;
use php\xml\DomElement;

class GameChangeAnimationActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_ANIMATION;
    }

    function attributes()
    {
        return [
            'object' => 'object',
            'animation' => 'string',
        ];
    }

    function attributeLabels()
    {
        return [
            'object' => 'Объект со спрайтом',
            'animation' => 'Название анимации'
        ];
    }

    function attributeSettings()
    {
        return [
            'object' => ['def' => '~sender'],
        ];
    }

    function getTagName()
    {
        return "GameChangeAnimation";
    }

    function getTitle(Action $action = null)
    {
        return 'Задать анимацию';
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Задать анимацию объекту";
        }

        return "Задать анимацию {$action->get('animation')} объекту {$action->get('object')}";
    }

    function getIcon(Action $action = null)
    {
        return "icons/filmChange16.png";
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "{$action->get('object')}->sprite->currentAnimation = {$action->get('animation')}";
    }
}