<?php

use php\gui\framework\Application;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXComboBox;
use php\gui\UXComboBoxBase;
use php\gui\UXDesktop;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXListView;
use php\gui\UXTab;
use php\gui\UXTextInputControl;
use php\lang\Process;
use php\lang\Thread;
use php\lib\Items;
use php\lib\Str;
use timer\AccurateTimer;

/**
 * @return Application
 * @throws Exception
 */
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
    return AccurateTimer::executeAfter($millis, $callback);
}

function uiLater(callable $callback)
{
    UXApplication::runLater($callback);
}

function uiValue($object)
{
    if (!$object) {
        return null;
    }

    if ($object instanceof ValuableBehaviour) {
        return $object->getObjectValue();
    }

    if ($object instanceof UXListView || $object instanceof UXComboBox) {
        return $object->selectedIndex;
    }

    if (property_exists($object, 'value')) {
        return $object->value;
    }

    return uiText($object);
}

function uiText($object)
{
    if (!$object) {
        return "";
    }

    if ($object instanceof TextableBehaviour) {
        return (string)$object->getObjectText();
    }

    if ($object instanceof UXLabeled || $object instanceof UXTextInputControl || $object instanceof UXTab) {
        return $object->text;
    }

    if ($object instanceof UXComboBoxBase) {
        return $object->editable ? $object->text : $object->value;
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

function pre($var)
{
    UXDialog::showAndWait(print_r($var, true));
}

function dump($var)
{
    ob_start();
    var_dump($var);
    $text = ob_get_contents();
    ob_end_clean();

    UXDialog::showAndWait($text);
}

function alert($message)
{
    UXDialog::showAndWait($message);
}

function message($message)
{
    UXDialog::showAndWait($message);
}