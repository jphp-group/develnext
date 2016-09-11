<?php
namespace ide\editors\form;

use game\SpriteManager;
use ide\formats\sprite\IdeSpriteManager;
use ide\misc\EventHandlerBehaviour;
use php\game\UXSprite;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXContextMenu;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXMenuItem;
use php\gui\UXTextField;
use php\gui\UXTitledPane;
use php\lib\Items;

/**
 * Class IdeSpritePane
 * @package ide\editors\form
 */
class IdeSpritePane
{
    use EventHandlerBehaviour;

    /**
     * @var UXVBox
     */
    protected $ui;

    /**
     * @var string
     */
    protected $sprite;

    /**
     * @var UXImageArea
     */
    protected $image;

    /**
     * @var IdeSpriteManager
     */
    protected $manager;

    /**
     * @var UXTextField
     */
    protected $spriteNameField;

    /**
     * IdeSpritePane constructor.
     * @param IdeSpriteManager $manager
     */
    public function __construct(IdeSpriteManager $manager = null)
    {
        $this->manager = $manager;
        $this->makeUi();
    }

    public function setSpriteImage(UXImage $image = null)
    {
        UXApplication::runLater(function () use ($image) {
            if ($image) {
                $this->image->image = $image;
            } else {
                $this->image->image = ico('grayQuestion16')->image;
            }
        });
    }

    public function setSprite($name)
    {
        $this->sprite = $name;

        $image = $this->manager ? $this->manager->getSpriteImage($name) : null;

        if ($this->manager) {
            $spriteSpec = $this->manager->get($name);

            if ($image) {
                $sprite = new UXSprite();
                $sprite->frameSize = [$spriteSpec->frameWidth, $spriteSpec->frameHeight];
                $sprite->image = $image;

                if ($sprite->frameCount > 0) {
                    $image = $sprite->getFrameImage(0);
                } else {
                    $image = null;
                }
            }
        }

        if ($this->spriteNameField) {
            $this->spriteNameField->text = $name ? $name : '<нет спрайта>';
        }

        $this->trigger('change', [$name ? $spriteSpec : null]);

        $this->setSpriteImage($image);
    }

    public function makeUi()
    {
        if ($this->ui) {
            return $this->ui;
        }

        $ui = new UXVBox();
        $ui->spacing = 10;

        if ($this->manager) {
            $actionPane = new UXHBox([
                $this->makeSelectButtonUi(),
                $this->makeSpriteFieldUi(),
            ]);

            $actionPane->spacing = 5;

            $ui->add($actionPane);
        }

        $ui->add($this->makeImageUi());

        $ui = new UXTitledPane('Спрайт', $ui);
        $ui->maxWidth = 1000;
        $ui->collapsible = false;
        $ui->graphic = ico('movie16');

        $this->setSprite(null);

        return $this->ui = $ui;
    }

    private function makeImageUi()
    {
        $border = new UXVBox();
        $border->alignment = 'BASELINE_CENTER';

        $image = new UXImageArea();

        $image->centered = true;
        $image->stretch = true;
        $image->smartStretch = true;
        $image->proportional = true;
        $image->size = [128, 128];

        $border->add($image);
        $border->size = [128, 128];
        $border->style = '-fx-border-color: gray; -fx-border-style: dashed; -fx-border-width: 1px;';

        $this->image = $image;

        return $border;
    }

    private function makeSpriteFieldUi()
    {
        $field = new UXTextField();
        $field->maxWidth = 9999;
        $field->editable = false;
        $field->text = $this->sprite ? $this->sprite : '<нет спрайта>';
        $field->enabled = false;

        $this->spriteNameField = $field;

        UXHBox::setHgrow($field, 'ALWAYS');

        return $field;
    }

    private function makeSelectButtonUi()
    {
        $button = new UXButton('');
        $button->graphic = ico('edit16');

        $menu = new UXContextMenu();

        $button->on('action', function (UXEvent $e) use ($menu) {
            $menu->items->clear();
            $clearItem = new UXMenuItem('<нет спрайта>');
            $clearItem->on('action', function () {
                $this->setSprite(null);
            });

            $menu->items->add($clearItem);
            $menu->items->add(UXMenuItem::createSeparator());

            foreach ($this->manager->getSprites() as $name => $spec) {
                $item = new UXMenuItem($name);

                $item->on('action', function () use ($name) {
                    $this->setSprite($name);
                });

                $menu->items->add($item);
            }

            $menu->showByNode($e->sender, 0, 30);
        });

        return $button;
    }

    private function makeClearButtonUi()
    {
        $button = new UXButton('Очистить');

        return $button;
    }
}