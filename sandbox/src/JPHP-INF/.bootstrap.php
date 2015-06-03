<?php

use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXLoader;
use php\io\Stream;

UXApplication::launch(function(UXForm $mainForm) {
    $mainForm->title = 'My App';

    $loader = new UXLoader();

    $mainForm->layout = $loader->load(Stream::of('res://forms/MyForm.fxml'));

    /** @var UXButton $button */
    $button = $mainForm->layout->lookup('#foobar');
    $button->on('click', function () {
        if (UXDialog::confirm('???')) {
            UXDialog::show('Yes!');
        }
    });

    $mainForm->centerOnScreen();
    $mainForm->show();
});