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
 * --RU--
 * Возвращает главный объект программы.
 *
 * @return Application
 * @throws Exception
 */
function app()
{
    return Application::get();
}

/**
 * Открывает файл.
 * @param string $file
 */
function open($file)
{
    (new UXDesktop())->open($file);
}

/**
 * Открывает url в браузере.
 * @param string $url
 */
function browse($url)
{
    (new UXDesktop())->browse($url);
}

/**
 * Выполняет команду в рамках ОС и возвращает процесс.
 * @param string $command
 * @param bool $wait
 * @return Process
 */
function execute($command, $wait = false)
{
    $process = new Process(Str::split($command, ' '));

    return $wait ? $process->startAndWait() : $process->start();
}

/**
 * --RU--
 * Пауза в выполнении кода в миллисекундах или во временном периоде.
 * 1 сек = 1000 млсек.
 *
 * for example '2h 30m 10s' or '2.5s' or '2000' or '1m 30s'
 *
 * @param int|string $period
 */
function wait($period)
{
    Thread::sleep(\php\time\Timer::parsePeriod($period));
}

/**
 * --RU--
 * Ассинхронная пауза в выполнении кода с колбэком.
 *
 * @param int|string $period
 * @param callable $callback
 * @return AccurateTimer
 */
function waitAsync($period, callable $callback)
{
    return AccurateTimer::executeAfter(\php\time\Timer::parsePeriod($period), $callback);
}

/**
 * --RU--
 * Выполнить колбэк позже в UI потоке.
 * Необходимо для работы с UI из других паралельных потоков.
 *
 * @param callable $callback
 */
function uiLater(callable $callback)
{
    UXApplication::runLater($callback);
}

/**
 * --RU--
 * Выполнить колбэк позже в UI потоке и ждать его выполнения и результата.
 * Необходимо для работы с UI из других паралельных потоков.
 *
 * @param callable $callback
 * @return mixed
 */
function uiLaterAndWait(callable $callback)
{
    return UXApplication::runLaterAndWait($callback);
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

/**
 * Показать значение переменной как print_r.
 * @param $var
 */
function pre($var)
{
    UXDialog::showAndWait(print_r($var, true));
}

/**
 * Показать значение переменной как var_dump.
 * @param $var
 */
function dump($var)
{
    ob_start();
    var_dump($var);
    $text = ob_get_contents();
    ob_end_clean();

    UXDialog::showAndWait($text);
}

/**
 * Простое сообщение с ожиданием закрытия.
 * @param $message
 */
function alert($message)
{
    UXDialog::showAndWait($message);
}

/**
 * Простое сообщение с ожиданием закрытия.
 * @param $message
 */
function message($message)
{
    UXDialog::showAndWait($message);
}