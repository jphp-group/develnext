<?php

use php\gui\layout\UXAnchorPane;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXFlatButton;
use php\gui\UXForm;
use php\gui\UXScreen;

echo "Hello World\n";

$bounds = UXScreen::getPrimary()->visualBounds;

$form = new UXForm();
$form->layout = new UXAnchorPane();
$form->layout->size = $form->size = [$bounds['width'], $bounds['height']];
$form->layout->backgroundColor = 'gray';

$form->addEventFilter('keyUp', function (\php\gui\event\UXKeyEvent $e) use ($form) {
    if ($e->codeName == 'Esc') {
        $form->hide();
    }
});

$button = new UXFlatButton();
$button->color = 'red';
$button->textColor = 'white';
$button->alignment = 'CENTER';
$button->text = 'Click Me!';
$button->size = [$bounds['width'], 50];
$button->y = 200;
$button->on('click', function (\php\gui\event\UXEvent $e) use ($form, $bounds) {
    UXDialog::show(print_r($bounds, true));
});

$form->add($button);

$form->show();