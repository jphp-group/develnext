<?php
namespace ide\action\types\media;

use action\Media;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

abstract class AbstractMediaAction extends AbstractSimpleActionType
{
    abstract public function getMediaMethod();

    function getHelpText()
    {
        return 'В качестве плеера вы можете указать символьный код или же выбрать среди компонентов модуля нужный плеер.';
    }

    function attributes()
    {
        return [
            'id' => 'mixed',
        ];
    }

    function attributeLabels()
    {
        return [
            'id' => 'Плеер'
        ];
    }

    function attributeSettings()
    {
        return [
            'id' => ['def' => 'general', 'defType' => 'string'],
        ];
    }

    function getGroup()
    {
        return self::GROUP_GAME;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_AUDIO;
    }

    function getTagName()
    {
        return $this->getMediaMethod() . "Media";
    }

    function imports()
    {
        return [
            Media::class
        ];
    }

    /**
     * @param Action $action
     * @param ActionScript $actionScript
     * @return string
     */
    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $id = $action->get('id');
        $method = $this->getMediaMethod();

        if ($id == "'general'" || $id == '"general"') {
            return "Media::$method()";
        } else {
            return "Media::$method($id)";
        }
    }
}