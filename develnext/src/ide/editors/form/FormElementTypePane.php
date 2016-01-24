<?php
namespace ide\editors\form;
use ide\editors\menu\ContextMenu;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\misc\EventHandlerBehaviour;
use ide\scripts\AbstractScriptComponent;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXComboBox;
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
    use EventHandlerBehaviour;

    /**
     * @var UXScrollPane
     */
    protected $content;

    /**
     * @var UXVBox
     */
    protected $layout;

    /**
     * @var UXTitledPane[]
     */
    protected $tiledPanes = [];

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

    /**
     * @var bool
     */
    protected $selectable;

    /**
     * @var mixed
     */
    protected $selected = null;

    /**
     * @var bool
     */
    protected $onlyIcons = false;

    /**
     * @var UXComboBox
     */
    protected $viewSelect;

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

    public function resetConfigurable($id)
    {
        $this->setOnlyIcons(Ide::get()->getUserConfigValue(get_class($this) . ".$id.onlyIcons", $this->isOnlyIcons()));
        $this->setOpenedGroups(Ide::get()->getUserConfigArrayValue(get_class($this) . ".$id.openedGroups", $this->getOpenedGroups()));
    }

    public function applyConfigure($id)
    {
        $this->resetConfigurable($id);

        $this->on('change', function () use ($id) {
            Ide::get()->setUserConfigValue(get_class($this) . ".$id.onlyIcons", $this->isOnlyIcons());
            Ide::get()->setUserConfigValue(get_class($this) . ".$id.openedGroups", $this->getOpenedGroups());
        }, __CLASS__);
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

    public function getOpenedGroups()
    {
        $groups = [];

        foreach ($this->tiledPanes as $group => $pane) {
            if ($pane->expanded) {
                $groups[$group] = $group;
            }
        }

        return $groups;
    }

    public function setOpenedGroups(array $groups)
    {
        foreach ($this->tiledPanes as $group => $pane) {
           $pane->expanded = in_array($group, $groups) || isset($groups[$group]);
        }
    }

    public function setOnlyIcons($value, $updateUi = true)
    {
        if ($value == $this->onlyIcons) {
            return;
        }

        $this->clearSelected();

        $this->onlyIcons = $value;

        foreach ($this->tiledPanes as $pane) {
            $pane->content = $value ? $pane->data('fbox') : $pane->data('vbox');
        }

        $this->trigger('change');

        if ($this->viewSelect && $updateUi) {
            $this->viewSelect->selectedIndex = $value ? 1 : 0;
        }
    }

    public function isOnlyIcons()
    {
        return $this->onlyIcons;
    }

    protected function createHeaderUi()
    {
        if ($this->selectable) {
            $vbox = new UXVBox();
            $vbox->spacing = 1;
            $vbox->padding = 2;

            /*$button = new UXToggleButton('Курсор');

            $button->toggleGroup = $this->toggleGroup;
            $button->graphic = Ide::get()->getImage('icons/cursor16.png');
            $button->height = 20;
            $button->maxWidth = 10000;
            $button->style = '-fx-cursor: hand; -fx-font-weight: bold;';
            $button->alignment = 'BASELINE_LEFT';
            $button->selected = true;
              */
            $this->unselectedButton = null;

            $this->viewSelect = $typeSelect = new UXComboBox(['Иконки + текст', 'Только иконки']);
            $typeSelect->maxWidth = 10000;
            $typeSelect->selectedIndex = 0;

            $typeSelect->on('action', function () use ($typeSelect) {
                $this->setOnlyIcons($typeSelect->selectedIndex == 1, false);
            });

            $vbox->add($typeSelect);

            $this->layout->add($vbox);
        }
    }

    protected function createGroupUi($group, $elements)
    {
        $vbox = new UXVBox();
        $vbox->spacing = 1;
        $vbox->padding = 2;

        $fbox = new UXFlowPane();
        $fbox->hgap = $fbox->vgap = 2;
        $fbox->padding = 2;

        /** @var AbstractFormElement $element */
        foreach ($elements as $element) {
            $button = $this->selectable ? new UXToggleButton($element->getName()) : new UXButton($element->getName());
            $smallButton = $this->selectable ? new UXToggleButton() : new UXButton();

            if ($this->selectable) {
                $button->toggleGroup = $this->toggleGroup;
                $smallButton->toggleGroup = $this->toggleGroup;
            }

            $button->classes->add('dn-simple-toggle-button');
            $button->height = 18;
            $button->maxWidth = 10000;
            $button->alignment = 'BASELINE_LEFT';
            $button->userData = $element;
            $button->graphic = Ide::get()->getImage($element->getIcon());
            $button->tooltipText = $element->getName();

            $smallButton->classes->add('dn-simple-toggle-button');
            $smallButton->size = [25, 30];
            $smallButton->userData = $element;
            $smallButton->graphic = Ide::get()->getImage($element->getIcon());
            $smallButton->tooltipText = $element->getName();

            $vbox->add($button);
            $fbox->add($smallButton);

            $this->buttons[] = $button;
            $this->buttons[] = $smallButton;
        }

        $pane = new UXTitledPane($group, $vbox);
        $pane->data('vbox', $vbox);
        $pane->data('fbox', $fbox);
        $pane->font = UXFont::of($pane->font->family, $pane->font->size, 'BOLD');
        $pane->animated = false;
        $pane->expanded = true;
        $pane->padding = [1, 3];

        /*$pane->observer('expanded')->addListener(function () {
            $this->trigger('change');
        }); */

        $this->tiledPanes[$group] = $pane;

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