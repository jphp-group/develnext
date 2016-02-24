<?php

use php\gui\framework\Application;
use php\gui\UXApplication;
use php\io\Stream;
use php\lang\System;

$app = Application::get();

$mainForm = $app->getMainForm();

if ($mainForm) {
    UXApplication::runLater(function () use ($mainForm) {
        if (!$mainForm->alwaysOnTop) {
            $mainForm->alwaysOnTop = true;
            $mainForm->alwaysOnTop = false;
        }

        $mainForm->requestFocus();
    });
}

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
    $dialog->setButtonTypes(['Выход из программы', 'Продолжить']);

    $pane = new UXAnchorPane();
    $pane->maxWidth = 100000;

    $content = new UXTextArea("{$e->getMessage()}\n\nОшибка в файле '{$e->getFile()}'\n\t-> на строке {$e->getLine()}\n\n" . $e->getTraceAsString());
    $content->padding = 10;
    UXAnchorPane::setAnchor($content, 0);

    $pane->add($content);
    $dialog->expandableContent = $pane;
    $dialog->expanded = true;

    switch ($dialog->showAndWait()) {
        case 'Выход из программы':
            Application::get()->shutdown();
            break;
    }

    $showed = false;
});


restore_exception_handler();
restore_exception_handler();*/