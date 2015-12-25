<?php

namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\layout\UXPanel;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXImageArea;
use php\gui\UXLabel;
use php\gui\UXSeparator;

class MyAccountCommand extends AbstractCommand
{
    /**
     * @var UXButton
     */
    protected $accountButton;

    /**
     * @var UXLabel
     */
    protected $accountLabel;

    /**
     * @var UXImageArea
     */
    protected $accountImage;

    /**
     * MyAccountCommand constructor.
     */
    public function __construct()
    {
        Ide::service()->on('privateEnable', function () {
            $this->accountButton->enabled = true;
        }, __CLASS__);

        Ide::service()->on('privateDisable', function () {
            $this->accountButton->enabled = false;
        }, __CLASS__);

        Ide::accountManager()->on('update', function ($data) {
            $this->accountButton->text = $data ? $data['name'] : 'Войти в аккаунт';

            Ide::service()->media()->loadImage($data['avatar'], $this->accountImage);

            if (!$this->accountImage->image) {
                $this->accountImage->image = Ide::get()->getImage('noAvatar.jpg')->image;
            }
        }, __CLASS__);
    }

    public function getName()
    {
        return 'Мой Аккаунт';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        Ide::accountManager()->authorize();
    }

    public function getIcon()
    {
        return 'icons/account16.png';
    }

    public function makeMenuItem()
    {
        return null;
    }

    public function makeUiForHead()
    {
        $btn = $this->makeGlyphButton();
        $btn->text = $this->getName();
        $btn->font = UXFont::of($btn->font->family, $btn->font->size, 'BOLD');

        $this->accountButton = $btn;
        $this->accountLabel = new UXLabel();
        $this->accountLabel->textColor = 'gray';
        $this->accountLabel->paddingLeft = 2;

        $this->accountImage = new UXImageArea();
        $this->accountImage->size = [32, 32];
        $this->accountImage->centered = true;
        $this->accountImage->proportional = true;
        $this->accountImage->stretch = true;
        $this->accountImage->smartStretch = true;
        $this->accountImage->position = [1, 1];

        $panel = new UXPanel();
        $panel->add($this->accountImage);
        $panel->borderWidth = 1;
        $panel->borderColor = 'silver';
        $panel->size = [32, 32];

        return [$panel, $btn, new UXSeparator('VERTICAL')];
    }

    public function isAlways()
    {
        return true;
    }
}