<?php
namespace ide\editors\form;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXNode;
use ide\formats\FormFormat;
use php\gui\layout\UXScrollPane;
use php\gui\UXTitledPane;
use php\gui\UXToggleButton;
use php\gui\UXToggleGroup;
use php\gui\UXTooltip;
use php\lib\Number;

/**
 * Class FormElementTypePane
 * @package ide\editors\form
 */
class FormElementTypePane
{
    /**
     * @var UXScrollPane
     */
    protected $content;

    /**
     * @var UXVBox
     */
    protected $layout;

    /**
     * @var UXToggleGroup
     */
    protected $toggleGroup;

    /**
     * @var UXToggleButton
     */
    protected $unselectedButton;

    /**
     * @param AbstractFormElement[] $elements
     */
    public function __construct(array $elements)
    {
        $this->toggleGroup = new UXToggleGroup();

        $this->layout = new UXVBox();
        $this->layout->maxWidth = 250;
        $this->layout->fillWidth = true;

        $this->content = new UXScrollPane($this->layout);
        $this->content->fitToWidth = true;

        $groups = [];

        /** @var AbstractFormElement $element */
        foreach ($elements as $element) {
            if ($element->getName()) {
                $groups[$element->getGroup()][] = $element;
            }
        }

        $this->createHeaderUi();

        foreach ($groups as $name => $elements) {
            $this->createGroupUi($name, $elements);
        }
    }

    /**
     * @return UXNode
     */
    public function getContent()
    {
        return $this->layout;
    }

    /**
     * @return AbstractFormElement
     */
    public function getSelected()
    {
        $selected = $this->toggleGroup->selected;

        if ($selected) {
            return $selected->userData;
        }

        return null;
    }

    public function clearSelected()
    {
        $this->toggleGroup->selected = $this->unselectedButton;
    }

    protected function createHeaderUi()
    {
        $vbox = new UXVBox();
        $vbox->spacing = 2;
        $vbox->padding = 5;

        $button = new UXToggleButton('Курсор');
        $button->toggleGroup = $this->toggleGroup;
        $button->graphic = Ide::get()->getImage('icons/cursor16.png');
        $button->height = 23;
        $button->maxWidth = 10000;
        $button->style = '-fx-cursor: hand; -fx-font-weight: bold;';
        $button->alignment = 'BASELINE_LEFT';
        $button->selected = true;

        $this->unselectedButton = $button;

        $vbox->add($button);

        $this->layout->add($vbox);
    }

    protected function createGroupUi($group, $elements)
    {
        $vbox = new UXVBox();
        $vbox->spacing = 2;
        $vbox->padding = 5;

        /** @var AbstractFormElement $element */
        foreach ($elements as $element) {
            $button = new UXToggleButton($element->getName());
            $button->toggleGroup = $this->toggleGroup;
            $button->height = 23;
            $button->maxWidth = 10000;
            $button->style = '-fx-cursor: hand;';
            $button->alignment = 'BASELINE_LEFT';

            $button->userData = $element;

            $button->graphic = Ide::get()->getImage($element->getIcon());

            $tooltip = new UXTooltip();
            $tooltip->text = $element->getName();

            $button->tooltip = $tooltip;

            $vbox->add($button);
        }

        $pane = new UXTitledPane($group, $vbox);
        $pane->animated = false;
        $pane->padding = [1, 3];

        $this->layout->add($pane);
    }
}