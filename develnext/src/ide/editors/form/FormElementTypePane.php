<?php
namespace ide\editors\form;
use ide\editors\menu\ContextMenu;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\scripts\AbstractScriptComponent;
use php\gui\event\UXMouseEvent;
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
     * @var UXButton[]
     */
    protected $buttons = [];

    protected $selectable;

    /**
     * @var mixed
     */
    protected $selected = null;

    /**
     * @param AbstractFormElement[]|AbstractScriptComponent[] $elements
     * @param bool $selectable
     */
    public function __construct(array $elements, $selectable = true)
    {
        $this->toggleGroup = new UXToggleGroup();
        $this->selectable = $selectable;

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
     * @return AbstractFormElement|AbstractScriptComponent
     */
    public function getSelected()
    {
        if ($this->selected) {
            return $this->selected;
        }

        $selected = $this->toggleGroup->selected;

        if ($selected) {
            return $selected->userData;
        }

        return null;
    }

    public function clearSelected()
    {
        $this->toggleGroup->selected = $this->unselectedButton;
        $this->selected = null;
    }

    protected function createHeaderUi()
    {
        if ($this->selectable) {
            $vbox = new UXVBox();
            $vbox->spacing = 1;
            $vbox->padding = 2;

            $button = new UXToggleButton('Курсор');

            $button->toggleGroup = $this->toggleGroup;
            $button->graphic = Ide::get()->getImage('icons/cursor16.png');
            $button->height = 20;
            $button->maxWidth = 10000;
            $button->style = '-fx-cursor: hand; -fx-font-weight: bold;';
            $button->alignment = 'BASELINE_LEFT';
            $button->selected = true;

            $this->unselectedButton = $button;

            $vbox->add($button);

            $this->layout->add($vbox);
        }
    }

    protected function createGroupUi($group, $elements)
    {
        $vbox = new UXVBox();
        $vbox->spacing = 1;
        $vbox->padding = 2;

        /** @var AbstractFormElement $element */
        foreach ($elements as $element) {
            $button = $this->selectable ? new UXToggleButton($element->getName()) : new UXButton($element->getName());

            if ($this->selectable) {
                $button->toggleGroup = $this->toggleGroup;
            }

            $button->height = 18;
            $button->maxWidth = 10000;
            $button->alignment = 'BASELINE_LEFT';

            $button->userData = $element;

            $button->graphic = Ide::get()->getImage($element->getIcon());

            $tooltip = new UXTooltip();
            $tooltip->text = $element->getName();

            $button->tooltip = $tooltip;

            $vbox->add($button);
            $this->buttons[] = $button;
        }

        $pane = new UXTitledPane($group, $vbox);
        $pane->animated = false;
        $pane->padding = [1, 3];

        $this->layout->add($pane);
    }

    public function setContextMenu(ContextMenu $contextMenu)
    {
        foreach ($this->buttons as $button) {
            $button->on('click', function (UXMouseEvent $e) use ($contextMenu, $button) {
                $target = $button;
                $this->selected = $button->userData;
                $contextMenu->getRoot()->show(Ide::get()->getMainForm(), $target->screenX, $target->screenY + $target->height);
            });
        }

        $contextMenu->getRoot()->on('hide', function () { $this->clearSelected(); });
    }
}