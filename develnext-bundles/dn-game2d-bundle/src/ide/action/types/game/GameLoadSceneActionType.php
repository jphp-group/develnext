<?php
namespace ide\action\types\game;

use game\Jumping;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\ObjectArgumentEditor;
use ide\editors\common\ObjectListEditorItem;
use ide\formats\form\elements\FormFormElement;
use ide\formats\form\elements\GamePaneFormElement;
use php\lib\str;

class GameLoadSceneActionType extends AbstractSimpleActionType
{
    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_COMMON;
    }

    function attributes()
    {
        return [
            'dest' => 'form',
            'source' => 'object',
        ];
    }

    function attributeLabels()
    {
        return [
            'dest' => 'Сцена',
            'source' => 'Объект (куда загрузить сцену)'
        ];
    }

    function attributeSettings()
    {
        return [
            'source' => ['def' => '~senderForm', 'editor' => function ($name, $label) {
                $editor = new ObjectArgumentEditor([
                    'formMethod'   => 'originForm',
                    'objectFilter' => function (ObjectListEditorItem $item) {
                        return $item->element instanceof GamePaneFormElement
                            || !$item->element;
                    }
                ]);
                return $editor;
            }],
        ];
    }

    function getTagName()
    {
        return "gameLoadScene";
    }

    function getTitle(Action $action = null)
    {
        return "Загрузить сцену";
    }

    function getDescription(Action $action = null)
    {
        return str::format("Загрузить игровую сцену из формы %s в объект %s", $action ? $action->get('dest') : '', $action ? $action->get('source') : '');
    }

    function getIcon(Action $action = null)
    {
        return 'icons/cinema16.png';
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        return "{$action->get('source')}->phys->loadScene({$action->get('dest')})";
    }
}