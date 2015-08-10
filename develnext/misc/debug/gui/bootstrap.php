<?php

use php\gui\framework\Application;
use php\gui\layout\UXAnchorPane;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXDialog;
use php\gui\UXTextArea;
use php\io\Stream;
use php\lang\System;

if (!function_exists('pre')) {
    function pre($var) {
        UXDialog::show(print_r($var, true));
    }
}

if (!function_exists('dump')) {
    function dump($var) {
        ob_start();
        var_dump($var);
        $text = ob_get_contents();
        ob_end_clean();

        UXDialog::show($text);
    }
}

if (!function_exists('alert')) {
    function alert($message) { UXDialog::show($message); }
}

Stream::putContents("application.pid", UXApplication::getPid());

$app = Application::get();

$mainForm = $app->getMainForm();

if (!$mainForm->alwaysOnTop) {
    $mainForm->alwaysOnTop = true;
    $mainForm->alwaysOnTop = false;
}

$mainForm->requestFocus();

/*set_exception_handler(function (BaseException $e) {
    static $showed = false;

    if ($showed) {
        return;
    }

    $showed = true;

    $dialog = new UXAlert('ERROR');
    $dialog->title = 'Ошибка';
    $dialog->headerText = 'Произошла ошибка в вашей программе';
    $dialog->contentText = $e->getMessage();
    $dialog->setButtonTypes(['Остановить', 'Игнорировать']);

    $pane = new UXAnchorPane();
    $pane->maxWidth = 100000;

    $content = new UXTextArea("Ошибка в файле '{$e->getFile()}'\n\t-> на строке {$e->getLine()}\n\n" . $e->getTraceAsString());
    $content->padding = 10;
    UXAnchorPane::setAnchor($content, 0);

    $pane->add($content);
    $dialog->expandableContent = $pane;
    $dialog->expanded = true;

    switch ($dialog->showAndWait()) {
        case 'Остановить':
            Application::get()->shutdown();
            break;
    }

    $showed = false;
});          */

restore_exception_handler();