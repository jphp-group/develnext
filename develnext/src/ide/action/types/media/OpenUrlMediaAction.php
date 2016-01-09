<?php
namespace ide\action\types\media;

use action\Media;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class OpenUrlMediaAction extends OpenMediaAction
{
    function getHelpText()
    {
        return 'В качестве плеера вы можете указать символьный код или же выбрать среди компонентов модуля нужный плеер.';
    }

    function attributeLabels()
    {
        return [
            'source' => 'Ссылка на аудио-файл (http, https, ftp)',
            'autoplay' => 'Воспроизвести после открытия',
            'id' => 'Плеер'
        ];
    }

    function attributeSettings()
    {
        return [
            'id' => ['def' => 'general', 'defType' => 'string'],
            'source' => ['editor' => 'string'],
            'autoplay' => ['def' => true]
        ];
    }


    function getTagName()
    {
        return "openUrlMedia";
    }

    function getTitle(Action $action = null)
    {
        return "Открыть аудио ссылку";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Открыть и воспроизвести аудио ссылку";
        }

        $source = $action->get('source');

        if ($action->autoplay) {
            return str::format("Открыть аудио ссылку %s и воспроизвести, плеер %s", $source, $action->get('id'));
        } else {
            return str::format("Открыть аудио ссылку %s, плеер %s", $source, $action->get('id'));
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/audioUrl16.png";
    }
}