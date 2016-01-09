<?php
namespace ide\action\types\media;

use action\Media;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionScript;
use php\lib\str;

class OpenFileMediaAction extends OpenMediaAction
{
    function getHelpText()
    {
        return 'Вы можете указать как относительный путь к аудио файлу, так и полный путь';
    }

    function attributeLabels()
    {
        return [
            'source' => 'Аудио файл (*.mp3, *.wav, *.wave, *.aif, *.aiff)',
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
        return "openFileMedia";
    }

    function getTitle(Action $action = null)
    {
        return "Открыть аудио файл";
    }

    function getDescription(Action $action = null)
    {
        if (!$action) {
            return "Открыть и воспроизвести аудио файл";
        }

        $source = $action->get('source');

        if ($this->autoplay) {
            return str::format("Открыть аудио файл %s и воспроизвести, плеер %s", $source, $action->get('id'));
        } else {
            return str::format("Открыть аудио файл %s, плеер %s", $source, $action->get('id'));
        }
    }

    function getIcon(Action $action = null)
    {
        return "icons/audioOpen16.png";
    }
}