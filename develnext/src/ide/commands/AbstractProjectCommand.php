<?php
namespace ide\commands;


use ide\Ide;
use ide\misc\AbstractCommand;

abstract class AbstractProjectCommand extends AbstractCommand
{
    public function makeGlyphButton()
    {
        $button = parent::makeGlyphButton();
        $button->enabled = false;

        Ide::get()->on('openProject', function () use ($button) {
            $button->enabled = true;
        }, get_class($this) . "_a");

        Ide::get()->on('closeProject', function () use ($button) {
            $button->enabled = false;
        }, get_class($this) . "_a");

        return $button;
    }

    public function makeMenuItem()
    {
        $item = parent::makeMenuItem();
        $item->disable = true;

        Ide::get()->on('openProject', function () use ($item) {
            $item->disable = false;
        }, get_class($this) . "_b");

        Ide::get()->on('closeProject', function () use ($item) {
            $item->disable = true;
        }, get_class($this) . "_b");

        return $item;
    }
}