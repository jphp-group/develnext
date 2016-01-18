<?php
namespace ide\action\types\media;

use action\Media;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class OpenMediaAction extends AbstractSimpleActionType
{
    function getHelpText()
    {
        return 'В качестве плеера вы можете указать символьный код или же выбрать среди компонентов модуля нужный плеер.';
    }

    function attributes()
    {
        return [
            'source' => 'string',
            'id'     => 'mixed',
            'autoplay' => 'flag',
        ];
    }

    function attributeLabels()
    {
        return [
            'source' => 'Аудио ресурс',
            'autoplay' => 'Воспроизвести после открытия',
            'id' => 'Плеер'
        ];
    }

    function attributeSettings()
    {
        return [
            'id' => ['def' => 'general', 'defType' => 'string'],
            'source' => ['editor' => 'audio'],
            'autoplay' => ['def' => true]
        ];
    }

    function getGroup()
    {
        return self::GROUP_MEDIA;
    }

    function getSubGroup()
    {
        return self::SUB_GROUP_AUDIO;
    }

    function getTagName()
    {
        return "openMedia";
    }

    function getTitle(Action $action = null)
    {
        return "Открыть аудио ресурс";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Открыть и воспроизвести аудио ресурс";
        }

        $source = $action->get('source');

        if ($action->autoplay) {
            return str::format("Открыть аудио ресурс %s и воспроизвести, плеер %s", $source, $action->get('id'));
        } else {
            return str::format("Открыть аудио ресурс %s, плеер %s", $source, $action->get('id'));
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/audio16.png";
    }

    function imports(Action $action = null)
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

        if ($id == "'general'" || $id == '"general"') {
            if ($action->autoplay) {
                return "Media::open({$action->get('source')})";
            } else {
                return "Media::open({$action->get('source')}, false)";
            }
        } else {
            if ($action->autoplay) {
                return "Media::open({$action->get('source')}, true, $id)";
            } else {
                return "Media::open({$action->get('source')}, false, $id)";
            }
        }
    }
}