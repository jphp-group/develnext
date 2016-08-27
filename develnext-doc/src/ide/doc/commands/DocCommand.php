<?php
namespace ide\doc\commands;

use ide\doc\editors\DocEditor;
use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\systems\FileSystem;
use ide\ui\Notifications;
use php\gui\event\UXKeyEvent;
use php\gui\layout\UXHBox;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXSeparator;
use php\gui\UXTextField;

class DocCommand extends AbstractCommand
{
    public function isAlways()
    {
        return true;
    }

    public function getName()
    {
        return 'Справка';
    }

    public function getCategory()
    {
        return 'help';
    }

    public function getIcon()
    {
        return 'icons/help16.png';
    }

    public function getAccelerator()
    {
        return 'F1';
    }

    protected function makeSearchInputUi()
    {
        $input = new UXTextField();
        $input->promptText = 'поиск в справке';
        $input->width = 170;
        $input->maxHeight = 999;
        $input->font = UXFont::of($input->font->family, 15);

        return $input;
    }

    public function makeUiForRightHead()
    {
        $button = $this->makeGlyphButton();
        $button->text = $this->getName();
        $button->maxHeight = 999;
        $button->padding = [0, 15];

        //$button->style = '-fx-font-weight: bold;';
        //$button->width = 35;

        $searchButton = new UXButton();
        $searchButton->classes->addAll(['icon-flat-search']);
        $searchButton->tooltipText = 'Поиск по документации';
        $searchButton->maxHeight = 999;
        $searchButton->width = 35;

        $input = $this->makeSearchInputUi();

        $input->observer('text')->addListener(function ($old, $new) use ($input) {
            if ($new) {
                $input->width = 250;
            } else {
                $input->width = 170;
            }

            Ide::get()->setUserConfigValue(__CLASS__ . '.searchQuery', $input->text);
        });

        $input->text = Ide::get()->getUserConfigValue(__CLASS__ . '.searchQuery', '');

        $searchHandle = function () use ($input) {
            /** @var DocEditor $editor */
            $param = ['search' => $input->text];
            FileSystem::openOrRefresh('~doc', $param);
        };

        $input->on('keyDown', function (UXKeyEvent $e) use ($searchHandle) {
            if ($e->codeName == 'Enter') {
                $searchHandle();
            }
        });

        $searchButton->on('action', $searchHandle);

        $ui = new UXHBox([$searchButton, $input, $button]);
        $ui->spacing = 5;
        $ui->fillHeight = true;

        return $ui;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        FileSystem::openOrRefresh('~doc');
    }
}