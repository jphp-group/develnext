<?php
namespace ide\action\types\media;


use ide\action\Action;
use php\lib\str;

class StopMediaAction extends AbstractMediaAction
{
    function getTitle(Action $action = null)
    {
        return !$action ? "Стоп" : str::format("Стоп, плеер = %s", $action->get('id'));
    }

    function getDescription(Action $action = null)
    {
        return !$action ? "Полностью остановить воспроизведение (stop)"
            : str::format("Остановить воспроизведение (stop), плеер = %s", $action->get('id'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/audioStop16.png";
    }

    public function getMediaMethod()
    {
        return "stop";
    }
}