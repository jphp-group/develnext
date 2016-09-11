<?php
namespace ide\action\types\media;


use ide\action\Action;
use ide\action\ActionScript;
use ide\editors\argument\MethodsArgumentEditor;
use php\lib\str;

class IfStatusMediaAction extends AbstractMediaAction
{
    function attributes()
    {
        return [
            'id'     => 'mixed',
            'status' => 'string',
        ];
    }

    function attributeLabels()
    {
        return [
            'id' => 'Плеер',
            'status' => 'Статус плеера'
        ];
    }

    function attributeSettings()
    {
        return [
            'status' => ['def' => 'PLAYING', 'editor' => function () {
                $editor = new MethodsArgumentEditor([
                    'UNKNOWN' => 'Неизвестно [UNKNOWN]',
                    'READY' => 'Подготовлен [READY]',
                    'PLAYING' => 'Играет [PLAYING]',
                    'PAUSED' => 'На паузе [PAUSED]',
                    'STOPPED' => 'Остановлен [STOPPED]',
                    'HALTED' => 'Возникла ошибка [HALTED]',
                ]);
                return $editor;
            }],
            'id' => ['def' => 'general', 'defType' => 'string'],
        ];
    }


    function getTitle(Action $action = null)
    {
        return !$action ? "Если плеер" : str::format("Если плеер %s", $action->get('id'));
    }

    function getDescription(Action $action = null)
    {
        return !$action ? "Если статус плеера" : str::format("Если статус плеера %s = %s", $action->get('id'), $action->get('status'));
    }

    function getIcon(Action $action = null)
    {
        return "icons/audioIf16.png";
    }

    public function getMediaMethod()
    {
        return "ifStatus";
    }

    function isAppendSingleLevel()
    {
        return true;
    }

    function convertToCode(Action $action, ActionScript $actionScript)
    {
        $id = $action->get('id');
        $status = $action->get('status');

        if ($id == "'general'" || $id == '"general"') {
            return "if (Media::isStatus($status))";
        } else {
            return "if (Media::isStatus($status, $id)";
        }
    }
}