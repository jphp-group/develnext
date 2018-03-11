<?php
namespace ide\action\types\media;


use ide\action\Action;
use php\lib\str;

class PauseMediaAction extends AbstractMediaAction
{
    function getTitle(Action $action = null)
    {
        return !$action ? "Пауза" : str::format("Пауза, плеер = %s", $action->get('id'));
    }

    function getDescription(Action $action = null)
    {
        return !$action ? "Поставить на паузу (pause)" : str::format("Поставить на паузу (pause), плеер = %s", $action->get('id'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/audioPause16.png";
    }

    public function getMediaMethod()
    {
        return "pause";
    }
}