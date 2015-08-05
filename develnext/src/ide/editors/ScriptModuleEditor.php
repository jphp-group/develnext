<?php
namespace ide\editors;

use ide\forms\MainForm;
use ide\Ide;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use ide\scripts\ScriptComponentManager;
use php\gui\designer\UXDesignProperties;
use php\gui\framework\AbstractScript;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCell;
use php\gui\UXLabel;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\lib\Items;

class ScriptModuleEditor extends AbstractEditor
{
    /** @var ScriptComponentManager */
    protected $manager;

    /**
     * @var UXListView
     */
    protected $scriptList;

    /**
     * @var UXDesignProperties[]
     */
    protected static $typeProperties = [];

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct($file);

        $this->manager = new ScriptComponentManager();
    }

    public static function initializeComponent(AbstractScriptComponent $component)
    {
        $properties = new UXDesignProperties();
        $component->createProperties($properties);

        return static::$typeProperties[get_class($component)] = $properties;
    }

    public static function getTypeProperties(AbstractScriptComponent $component)
    {
        if ($properties = static::$typeProperties[get_class($component)]) {
            return $properties;
        }

        return static::initializeComponent($component);
    }


    public function load()
    {
        $this->manager->removeAll();
        $this->manager->updateByPath($this->file);
    }

    public function open()
    {
        $this->updateProperties();

        $this->scriptList->items->clear();
        $this->scriptList->items->addAll($this->manager->getComponents());
    }


    public function save()
    {
        ;
    }

    protected function updateProperties(ScriptComponentContainer $script = null)
    {
        /** @var MainForm $mainForm */
        $mainForm = Ide::get()->getMainForm();
        $pane = $mainForm->getPropertiesPane();

        /** @var UXTabPane $tabs */
        $tabs = $pane->lookup('#tabs');
        $selectedEvent = null;

        if ($tabs) {
            $selectedIndex = $tabs->selectedIndex;

            $eventTab = $tabs->tabs[1];

            if ($eventTab && $eventTab->content) {
                $list = $eventTab->content->lookup('#list');

                if ($list instanceof UXListView) {
                    $selected = Items::first($list->selectedItems);

                    if ($selected) {
                        $selectedEvent = $selected['type']['code'];
                    }
                }
            }
        } else {
            $selectedIndex = -1;
        }

        $properties = $script ? static::getTypeProperties($script->type) : null;

        if ($properties) {
            $properties->update();
        }

        $pane->children->clear();

        $tabs = new UXTabPane();
        $tabs->id = 'tabs';

        UXAnchorPane::setAnchor($tabs, 0);

        if ($properties) {
            $propTab = new UXTab();
            $propTab->text = 'Свойства';
            $propTab->content = new UXVBox();
            $propTab->content->spacing = 2;
            $propTab->closable = false;

            foreach ($properties->getGroupPanes() as $groupPane) {
                $propTab->content->children->add($groupPane);
            }

            $tabs->tabs->add($propTab);
        }

        $eventTypes = $script ? $script->type->getEventTypes() : [];

        if ($eventTypes) {
            $eventTab = new UXTab();
            $eventTab->text = 'События';
            $eventTab->closable = false;

            $tabs->tabs->add($eventTab);
        }

        if ($tabs->tabs && $script) {
            $pane->add($tabs);

            if ($selectedIndex > -1) {
                $tabs->selectedIndex = $selectedIndex;
            }
        }

        if ($eventTypes && $script) {
            //$this->updateEventTypes($node, $selectedEvent);
        }

        if (!$properties || !$properties->getGroupPanes()) {
            $hint = new UXLabel('Список пуст.');

            if ($script === null) {
                $hint->text = '...';
            }

            $hint->style = '-fx-font-style: italic;';
            $hint->maxSize = [10000, 10000];
            $hint->padding = 20;
            $hint->alignment = 'BASELINE_CENTER';

            $pane->children->add($hint);
        }
    }

    public function scriptListCellFactory(UXCell $cell, ScriptComponentContainer $script = null)
    {
        if ($script) {
            $cell->text = null;

            $name = new UXLabel($script->id);
            $name->css('font-weight', 'bold');

            $type = new UXLabel('[' . get_class($script->type) . ']');
            $type->textColor = 'gray';

            $description = new UXLabel($script->type->getDescription());
            $description->textColor = 'silver';
            $description->css('font-style', 'italic');

            $line = new UXHBox();

            if ($script->type->getIcon()) {
                $line->add(Ide::get()->getImage($script->type->getIcon()));
            }

            $line->add(new UXVBox([
                new UXHBox([$name, $type]),
                $description
            ]));

            $cell->graphic = $line;
        }
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $ui = new UXAnchorPane();

        $this->scriptList = new UXListView();
        $this->scriptList->setCellFactory([$this, 'scriptListCellFactory']);

        $this->scriptList->on('mouseUp', function () {
            $script = $this->manager->getComponents()[$this->scriptList->focusedIndex];
            $this->updateProperties($script);
        });

        UXAnchorPane::setAnchor($this->scriptList, 10);
        $this->scriptList->topAnchor = 50;

        $ui->add($this->scriptList);

        $actions = new UXHBox();
        $actions->height = 30;
        $actions->spacing = 5;
        $actions->fillHeight = true;

        $actions->topAnchor = 10;
        $actions->leftAnchor = $actions->rightAnchor = 10;

        $addButton = new UXButton('Добавить скрипт');
        $addButton->graphic = Ide::get()->getImage('icons/plus16.png');
        $addButton->height = 30;
        $actions->add($addButton);

        $ui->add($actions);

        return $ui;
    }
}