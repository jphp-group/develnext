<?php
namespace ide\webplatform\formats\form\views;

use php\gui\effect\UXDropShadowEffect;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\UXFlatButton;
use php\gui\UXLabel;

/**
 * Class SwitchWebElementView
 * @package ide\webplatform\formats\form\views
 *
 * @property bool $selected
 * @property string $text
 * @property int $iconSize
 * @property int $iconGap
 * @property string $iconDisplay
 */
class SwitchWebElementView extends UXHBox
{
    /**
     * @var UXFlatButton
     */
    protected $circle;

    /**
     * @var UXFlatButton
     */
    protected $rect;

    /**
     * @var UXAnchorPane
     */
    protected $iconPane;

    /**
     * @var UXLabel
     */
    protected $label;

    /**
     * @var array
     */
    protected $state = [
        'selected' => false,
        'iconSize' => 16,
        'iconGap'  => 8,
        'iconDisplay' => 'left',
        'text' => '',
        'font' => null,
        'underline' => false
    ];

    /**
     * SwitchWebElementView constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->data('--node-instance', $this);

        $this->createUi();
    }

    protected function createUi()
    {
        $this->circle = $circle = new UXFlatButton();
        $circle->effects->add(new UXDropShadowEffect(4, 'gray', 0, 0));
        $circle->mouseTransparent = true;

        $this->rect = $rect = new UXFlatButton();
        $rect->mouseTransparent = true;

        $this->maxWidth = $this->minWidth = $this->width;
        $this->maxHeight = $this->minHeight = $this->height;
        $this->backgroundColor = null;

        $label = $this->label = new UXLabel();
        $label->mouseTransparent = true;
        $this->state['font'] = $label->font;

        $this->iconPane = $iconPane = new UXAnchorPane();
        $iconPane->add($rect);
        $iconPane->add($circle);
        $iconPane->maxHeight = -INF;

        $this->add($iconPane);
        $this->add($label);

        $this->alignment = 'CENTER_LEFT';

        $this->updateState($this->state);
    }

    protected function updateState(array $state)
    {
        $this->rect->backgroundColor = $state['selected'] ? '#bbbbbb' : '#b3b3b3';
        $this->rect->borderRadius = $state['iconSize'] * 0.5;
        $this->rect->width = $state['iconSize'] * 2.5;
        $this->rect->height = $state['iconSize'];
        $this->rect->y = $state['iconSize'] * 0.25;

        $this->circle->backgroundColor = $state['selected'] ? '#777' : 'white';
        $this->circle->borderRadius = $state['iconSize'];
        $this->circle->size = [$state['iconSize'] * 1.5, $state['iconSize'] * 1.5];
        $this->circle->x = $state['selected'] ? $state['iconSize'] : 0;

        $this->label->text = $state['text'];
        $this->label->font = $state['font'];
        $this->label->underline = $state['underline'];

        $this->iconPane->height = $state['iconSize'] * 1.5;
        $this->iconPane->width = $state['iconSize'] * 2.5;

        $this->prefWidth = $this->minWidth = $this->iconPane->width;
        $this->prefHeight = $this->minHeight = $this->iconPane->height;
        //$this->iconPane->style = '-fx-border-width: 1; -fx-border-color: red;';

        switch ($state['iconDisplay']) {
            case "left":
                $this->iconPane->free();
                $this->children->insert(0, $this->iconPane);
                break;

            case "right":
                $this->iconPane->free();
                $this->children->add($this->iconPane);
                break;
        }

        $this->spacing = $state['iconGap'] + 1;
    }

    public function __get($name)
    {
        if (isset($this->state[$name])) {
            return $this->state[$name];
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (isset($this->state[$name])) {
            $this->state[$name] = $value;
            $this->updateState($this->state);
            return;
        }

        parent::__set($name, $value);
    }
}