<?php

use php\graphic\Image;
use php\gui\UXApplication;
use php\gui\UXForm;
use php\gui\UXImageView;

UXApplication::runLater(function () {
    $image = new UXImageView(Image::open('res://test.jpg'));

    $image->image = $image->image->toNativeImage()->rotate(90);

    $form = new UXForm();
    $form->layout->add($image);
    $form->size = [600, 400];

    $form->showAndWait();
});