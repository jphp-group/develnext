<?php

use php\gui\framework\Application;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXComboBox;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXListView;
use php\gui\UXTextInputControl;
use php\lang\Process;
use php\lang\Thread;
use php\lib\Items;
use php\lib\Str;

function app()
{
    return Application::get();
}

function open($file)
{
    (new UXDesktop())->open($file);
}

function browse($url)
{
    (new UXDesktop())->browse($url);
}

function execute($command, $wait = false)
{
    $process = new Process(Str::split($command, ' '));

    return $wait ? $process->startAndWait() : $process->start();
}

function wait($millis)
{
    Thread::sleep($millis);
}

function waitAsync($millis, callable $callback)
{
    (new Thread(function () use ($millis, $callback) {
        Thread::sleep($millis);
        UXApplication::runLater($callback);
    }))->start();
}

function uiText($object)
{
    if (!$object) {
        return "";
    }

    if ($object instanceof TextableBehaviour) {
        return (string) $object->getObjectText();
    }

    if ($object instanceof UXLabeled || $object instanceof UXTextInputControl) {
        return $object->text;
    }

    if ($object instanceof UXComboBox) {
        return $object->value;
    }

    if ($object instanceof UXListView) {
        return Items::first($object->selectedItems);
    }

    return "$object";
}

function uiConfirm($message)
{
    $alert = new UXAlert('CONFIRMATION');
    $alert->headerText = $alert->title = 'Вопрос';
    $alert->contentText = $message;
    $buttons = ['Да', 'Нет'];

    $alert->setButtonTypes($buttons);

    return $alert->showAndWait() == $buttons[0];
}