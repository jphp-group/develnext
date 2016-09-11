<?php
namespace ide\action\types\media;


use ide\action\Action;
use php\lib\str;

class PlayMediaAction extends AbstractMediaAction
{
    function getTitle(Action $action = null)
    {
        return !$action ? "Воспроизвести" : str::format("Воспроизвести, плеер = %s", $action->get('id'));
    }

    function getDescription(Action $action = null)
    {
        return !$action ? "Воспроизвести (play)" : str::format("Воспроизвести (play), плеер = %s", $action->get('id'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/audioPlay16.png";
    }

    public function getMediaMethod()
    {
        return "play";
    }
}