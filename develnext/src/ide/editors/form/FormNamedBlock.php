<?php
namespace ide\editors\form;

use ide\Ide;
use php\gui\event\UXEvent;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\UXApplication;
use php\gui\UXImageView;
use php\gui\UXLabel;

/**
 * Class FormNamedBlock
 * @package ide\editors\form
 */
class FormNamedBlock extends UXAnchorPane
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var mixed
     */
    protected $icon;

    /**
     * FormNamedBlock constructor.
     * @param $title
     * @param $icon
     */
    public function __construct($title, $icon)
    {
        parent::__construct();

        $this->maxSize = $this->minSize = $this->size = [32, 32];
        $this->style = '-fx-border-width: 1px; -fx-border-color: gray; -fx-background-color: silver; -fx-border-radius: 3px; cursor: hand;';

        $label = new UXLabel($title);
        $label->id = 'title';
        $label->padding = [2, 5];
        $label->style = '-fx-border-width: 1px; -fx-border-color: gray; -fx-background-color: white; -fx-border-style: dashed; cursor: hand;';
        $label->mouseTransparent = true;

        $this->add($label);

        $this->setIcon($icon);
        $this->setTitle($title);

        $this->watch('width', function () use ($label) {
            $centerX = $this->width / 2;

            $width = $label->font->calculateTextWidth($label->text) + 5 * 2;
            $label->x = round($centerX - $width / 2);
        });

        $this->watch('height', function () use ($label) {
            $label->y = $this->height + 7;
        });

        $mouseUp = function () {
            UXApplication::runLater(function () {
                $this->parent->requestFocus();
            });
        };

        $label->on('click', $mouseUp);
        $this->on('click', $mouseUp);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        /** @var UXLabel $label */
        $label = $this->lookup('#title');
        $label->text = $title;

        $centerX = $this->width / 2;

        $width = $label->font->calculateTextWidth($label->text) + 5 * 2;
        $label->x = round($centerX - $width / 2);
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        $old = $this->lookup("#icon");

        if ($old) {
            $this->remove($old);
        }

        /** @var UXImageView $icon */
        $icon = Ide::get()->getImage($icon);

        if ($icon) {
            $icon->id = 'icon';
            $icon->style = 'cursor: hand;';
            $icon->mouseTransparent = true;
            $icon->position = [8, 8];

            $icon->on('mouseUp', function () {
                $this->parent->requestFocus();
            });
            $this->add($icon);
        }
    }
}