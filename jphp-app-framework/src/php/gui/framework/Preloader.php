<?php
namespace php\gui\framework;

use php\gui\layout\UXAnchorPane;
use php\gui\UXForm;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXParent;
use php\gui\UXProgressIndicator;

/**
 * Class Preloader
 * @package php\gui\framework
 */
class Preloader extends UXAnchorPane
{
    /**
     * @var UXNode
     */
    protected $pane;

    /**
     * Preloader constructor.
     * @param UXParent $pane
     * @param string $text
     */
    public function __construct($pane, $text = '')
    {
        parent::__construct();

        $this->pane = $pane;
        $pane->data('--preloader', $this);

        UXAnchorPane::setAnchor($this, 0);

        $this->size = $pane->size;
        $this->visible = false;

        $this->position = [0, 0];
        $this->opacity = 0.6;
        $this->visible = false;

        $indicator = new UXProgressIndicator();
        $indicator->progress = -1;
        $indicator->size = [48, 48];

        $label = null;

        if ($text) {
            $label = new UXLabel($text);
            $label->text = $text;
            $this->add($label);
        }

        $this->watch('width', function () use ($pane, $indicator, $label, $text) {
            $indicator->x = $pane->width / 2 - $indicator->width / 2;

            if ($label) {
                $label->x = $pane->width / 2 - $label->font->calculateTextWidth($text) / 2;
            }
        });

        $this->watch('height', function () use ($pane, $indicator, $label) {
            $indicator->y = $pane->height / 2 - $indicator->height / 2;

            if ($label) {
                $label->y = $indicator->y + $indicator->height + 5;
            }
        });

        $this->add($indicator);

        $this->id = 'x-preloader';
        $this->style = '-fx-background-color: white';

        $pane->add($this);
    }

    public function show()
    {
        parent::show();

        $this->toFront();
    }

    static function hidePreloader(UXNode $pane)
    {
        $preloader = $pane->data('--preloader');

        if ($preloader) {
            $preloader->free();
        }
    }
}