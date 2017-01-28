<?php
namespace ide\editors\form;

use ide\editors\common\ObjectListEditorItem;
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
use php\gui\UXButtonBase;
use php\gui\UXComboBox;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXNode;
use php\gui\layout\UXScrollPane;
use php\gui\UXSplitPane;
use php\gui\UXTextField;
use php\gui\UXTitledPane;
use php\gui\UXToggleButton;
use php\gui\UXToggleGroup;
use php\gui\UXTooltip;
use php\lib\arr;
use php\lib\Number;
use php\lib\str;

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
     * @var UXTextField
     */
    protected $searchField;

    /**
     * @param AbstractFormElement[]|AbstractScriptComponent[]|ObjectListEditorItem[] $elements
     * @param bool $selectable
     * @param UXToggleGroup $toggleGroup
     */
    public function __construct(array $elements, $selectable = true, UXToggleGroup $toggleGroup = null)
    {
        $this->toggleGroup = $toggleGroup ?: new UXToggleGroup();
        $this->selectable = $selectable;

        $this->layout = new UXVBox();
        $this->layout->maxWidth = 265;
        $this->layout->fillWidth = true;

        $this->content = new UXScrollPane($this->layout);
        $this->content->fitToWidth = true;

        $this->createHeaderUi();

        $this->setElements($elements);
    }

    /**
     * @return UXToggleGroup
     */
    public function getToggleGroup()
    {
        return $this->toggleGroup;
    }

    public function setElements(array $elements)
    {
        $this->tiledPanes = [];

        $this->toggleGroup->selected = null;

        $head = $this->layout->children[0];
        $this->layout->children->clear();

        $this->layout->add($head);

        if (!$elements) {
            $noLabel = new UXLabel("Список пуст.");
            $noLabel->padding = 10;
            $this->layout->add($noLabel);
        }

        $groups = [];

        /** @var AbstractFormElement $element */
        foreach ($elements as $element) {
            if ($element->getName()) {
                $groups[$element->getGroup()][] = $element;
            }
        }

        foreach ($groups as $name => $elements) {
            $this->createGroupUi($name, $elements);
        }

        $text = $this->searchField->text;
        $this->searchField->text = "";
        $this->searchField->text = $text;
    }

    public function resetConfigurable($id)
    {
        $this->setOnlyIcons(Ide::get()->getUserConfigValue(get_class($this) . ".$id.onlyIcons", $this->isOnlyIcons()));
        $this->setOpenedGroups(Ide::get()->getUserConfigArrayValue(get_class($this) . ".$id.openedGroups", $this->getOpenedGroups()));

        $searchQuery = Ide::get()->getUserConfigValue(get_class($this) . ".$id.searchQuery", "");
        $this->searchField->text = $searchQuery;
    }

    public function applyConfigure($id)
    {
        $this->resetConfigurable($id);

        $this->on('change', function () use ($id) {
            Ide::get()->setUserConfigValue(get_class($this) . ".$id.onlyIcons", $this->isOnlyIcons());
            Ide::get()->setUserConfigValue(get_class($this) . ".$id.openedGroups", $this->getOpenedGroups());

            Ide::get()->setUserConfigValue(get_class($this) . ".$id.searchQuery", $this->searchField->text);
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

        foreach ($this->layout->children as $box) {
            if ($box->userData == 'searchResult') {
                $box->content = $value ? $box->data('fbox') : $box->data('vbox');
                break;
            }
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

    protected function findElements($query)
    {
        $result = [];
        $query = str::trim(str::lower($query));

        if (!$query) {
            return [];
        }

        foreach ($this->tiledPanes as $pane) {
            /** @var UXVBox|UXFlowPane $box */
            $box = $pane->content;

            foreach ($box->children as $one) {
                if ($one instanceof UXButtonBase) {
                    $text = str::lower($one->tooltipText);

                    $score = 0;

                    if (str::startsWith($text, $query)) {
                        $score += 1000;
                    }

                    if (str::length($query) > 2) {
                        $score += 100 * str::count($text, $query);
                    }

                    if ($pos = (str::pos($text, $query) + 1)) {
                        $score += 100 - $pos;
                    }

                    if ($score > 0) {
                        $result[] = [
                            'score' => $score,
                            'element' => $one->userData
                        ];
                    }
                }
            }
        }

        $result = arr::sort($result, function ($a, $b) {
            if ($a['score'] == $b['score']) return 0;
            return $a['score'] > $b['score'] ? -1 : 1;
        });

        return flow($result)->map(function ($el) {
            return $el['element'];
        })->toArray();
    }

    protected function loadSearchResultUi(array $result, $notFound = false)
    {
        $uiBox = null;

        foreach ($this->layout->children as $box) {
            if ($box->userData == 'searchResult') {
                $box->free();
                break;
            }
        }

        if ($notFound) {
            $label = new UXLabel('Ничего не найдено.');
            $label->padding = 10;

            $pane = new UXTitledPane('Результаты поиска', $label);
            $pane->padding = [1, 3];
        } else {
            $pane = $this->makeGroupUi('Результаты поиска', $result);
        }

        if ($pane) {
            $pane->font = UXFont::of($pane->font->family, $pane->font->size, 'BOLD');
            $pane->textColor = 'gray';
            $pane->animated = false;
            $pane->expanded = true;

            $pane->paddingBottom = 15;
            $pane->userData = 'searchResult';
            $this->layout->children->insert(1, $pane);
        }
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

            $this->searchField = $searchField = new UXTextField();
            $searchField->promptText = 'Поиск компонентов';
            $searchField->maxWidth = 10000;

            $searchField->observer('text')->addListener(function () use ($searchField) {
                $query = $searchField->text;

                $result = $this->findElements($query);
                $this->loadSearchResultUi($result, $query && !$result);

                uiLater(function () {
                    $this->trigger('change');
                });
            });

            $this->viewSelect = $typeSelect = new UXComboBox(['Иконки + текст', 'Только иконки']);
            $typeSelect->maxWidth = 10000;
            $typeSelect->selectedIndex = 0;

            $typeSelect->on('action', function () use ($typeSelect) {
                $this->setOnlyIcons($typeSelect->selectedIndex == 1, false);
            });

            $vbox->add($typeSelect);
            $vbox->add($searchField);

            $this->layout->add($vbox);
        }
    }

    protected function makeGroupUi($group, $elements, callable $buttonCreateCallback = null)
    {
        if (!$elements) {
            return null;
        }

        $vbox = new UXVBox();
        $vbox->spacing = 1;
        $vbox->padding = 2;

        $fbox = new UXFlowPane();
        $fbox->hgap = $fbox->vgap = 2;
        $fbox->padding = 2;

        /** @var AbstractFormElement|ObjectListEditorItem $element */
        foreach ($elements as $element) {
            $button = $this->selectable ? new UXToggleButton($element->getName()) : new UXButton($element->getName());
            $smallButton = $this->selectable ? new UXToggleButton() : new UXButton();

            if ($this->selectable) {
                $button->toggleGroup = $this->toggleGroup;
                $smallButton->toggleGroup = $this->toggleGroup;
            }

            $button->classes->add('dn-simple-toggle-button');
            $button->height = 16;
            $button->maxWidth = 10000;
            $button->alignment = 'BASELINE_LEFT';
            $button->userData = $element;

            $button->graphic = Ide::get()->getImage($element->getIcon());

            if ($button->graphic) {
                $button->graphic->size = [16, 16];
                $button->graphic->preserveRatio = true;
            }

            $button->tooltipText = $element->getName();


            if ($element instanceof ObjectListEditorItem) {
                $button->tooltipText .= ": " . $element->element->getName();
            } else if ($element->getElementClass()) {
                $button->tooltipText .= "\n{$element->getElementClass()}";
            }

            $smallButton->classes->add('dn-simple-toggle-button');
            $smallButton->size = [25, 30];
            $smallButton->userData = $element;
            $smallButton->graphic = Ide::get()->getImage($element->getIcon());

            if ($smallButton->graphic) {
                $smallButton->graphic->size = [16, 16];
                $smallButton->graphic->preserveRatio = true;
            }

            $smallButton->tooltipText = $element->getName();


            if ($element instanceof ObjectListEditorItem) {
                $smallButton->tooltipText .= ": " . $element->element->getName();
            } else if ($element->getElementClass()) {
                $smallButton->tooltipText .= "\n{$element->getElementClass()}";
            }

            $vbox->add($button);
            $fbox->add($smallButton);

            if ($buttonCreateCallback) {
                $buttonCreateCallback($button);
                $buttonCreateCallback($smallButton);
            }

            $this->buttons[] = $button;
            $this->buttons[] = $smallButton;
        }

        $pane = new UXTitledPane($group, $this->isOnlyIcons() ? $fbox : $vbox);
        $pane->data('vbox', $vbox);
        $pane->data('fbox', $fbox);
        $pane->font = UXFont::of($pane->font->family, $pane->font->size, 'BOLD');
        $pane->animated = false;
        $pane->expanded = true;
        $pane->padding = [1, 3];

        return $pane;
    }

    protected function createGroupUi($group, $elements)
    {
        $pane = $this->makeGroupUi($group, $elements);

        if ($pane) {
            $this->tiledPanes[$group] = $pane;
            $this->layout->add($pane);
        }
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

        $contextMenu->getRoot()->on('hide', function () {
            $this->clearSelected();
        });
    }
}