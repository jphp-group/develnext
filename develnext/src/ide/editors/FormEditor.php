<?php
namespace ide\editors;

use ide\editors\form\FormElementTypePane;
use ide\editors\menu\ContextMenu;
use ide\formats\AbstractFormFormat;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\FormEventManager;
use ide\formats\FormFormat;
use ide\formats\PhpCodeFormat;
use ide\forms\MainForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\ProjectFile;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDesignPane;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\DataUtils;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXContextMenu;
use php\gui\UXData;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXList;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXLoader;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXPopupWindow;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTooltip;
use php\io\File;
use php\io\Stream;
use php\lib\Items;
use php\lib\Str;
use php\lib\String;
use php\time\Time;
use php\util\Configuration;
use php\util\Flow;

/**
 * Class FormEditor
 * @package ide\editors
 *
 * @property AbstractFormFormat $format
 */
class FormEditor extends AbstractModuleEditor
{
    const BORDER_SIZE = 8;

    protected $designerCodeEditor;

    /** @var UXSplitPane */
    protected $viewerAndEvents;

    /** @var UXTab */
    protected $designerTab, $codeTab;

    /** @var UXTabPane */
    protected $tabs;

    /**
     * @var string
     */
    protected $codeFile;

    /**
     * @var string
     */
    protected $configFile;

    /**
     * @var UXPane
     */
    protected $layout;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var UXDesigner
     */
    protected $designer;

    /**
     * @var FormElementTypePane
     */
    protected $elementTypePane;

    /**
     * @var ContextMenu
     */
    protected $contextMenu;

    /**
     * @var AbstractFormDumper
     */
    protected $formDumper;

    /**
     * @var CodeEditor
     */
    protected $codeEditor;

    /**
     * @var string
     */
    protected $tabOpened = null;

    /**
     * @var FormEventManager
     */
    protected $eventManager;

    /**
     * @var UXDesignProperties[]
     */
    protected static $typeProperties = [];

    public function __construct($file, AbstractFormDumper $dumper)
    {
        parent::__construct($file);

        $this->config = new Configuration();
        $this->formDumper = $dumper;

        $phpFile = $file;
        $confFile = $file;

        if (Str::endsWith($phpFile, '.fxml')) {
            $phpFile = Str::sub($phpFile, 0, Str::length($phpFile) - 5);
            $confFile = $phpFile;
        }

        $phpFile  .= '.php';
        $confFile .= '.conf';

        if ($file instanceof ProjectFile) {
            if ($link = $file->findLinkByExtension('php')) {
                $phpFile = $link;
            }

            if ($link = $file->findLinkByExtension('conf')) {
                $confFile = $link;
            }
        }

        $this->eventManager = new FormEventManager($phpFile);

        $this->codeFile = $phpFile;
        $this->configFile = $confFile;

        $this->codeEditor = Ide::get()->getRegisteredFormat(PhpCodeFormat::class)->createEditor($phpFile);
        $this->codeEditor->register(AbstractCommand::makeWithText('Скрыть', 'icons/exit16.png', function () {
            $this->codeEditor->save();
            $this->switchToDesigner(true);
        }));

        $this->codeEditor->on('update', function () {
            $node = $this->designer->pickedNode;
            $this->codeEditor->save();

            $this->updateEventTypes($node ? $node : $this);
        });
    }

    public function getTooltip()
    {
        $tooltip = new UXTooltip();
        $tooltip->text = (new File($this->file))->getPath();

        return $tooltip;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param UXPane $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return UXPane
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return AbstractFormDumper
     */
    public function getFormDumper()
    {
        return $this->formDumper;
    }

    /**
     * @return UXDesigner
     */
    public function getDesigner()
    {
        return $this->designer;
    }

    /**
     * @return UXNode
     */
    public function load()
    {
        $this->eventManager->load();
        $this->formDumper->load($this);

        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->load();
        }

        if (File::of($this->configFile)->exists()) {
            $this->config->load($this->configFile);
        }
    }

    public function deleteNode($node)
    {
        $designer = $this->designer;

        $designer->unselectNode($node);
        $designer->unregisterNode($node);

        DataUtils::remove($node);
        $node->parent->remove($node);

        if ($this->eventManager->removeBinds($node->id)) {
            $this->codeEditor->load();
        }
    }

    public function save()
    {
        $this->formDumper->save($this);

        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->save();
        }

        Stream::tryAccess($this->configFile, function (Stream $stream) {
            $this->config->save($stream);
        }, 'w+');
    }

    public function close()
    {
        parent::close();

        $this->updateProperties(null);
    }

    public function open()
    {
        parent::open();
        $this->designer->unselectAll();

        $this->eventManager->load();
        $this->updateProperties($this);
    }

    public function selectForm()
    {
        $this->designer->unselectAll();
        $this->updateProperties($this);
    }

    public function makeUi()
    {
        if (!$this->layout) {
            throw new \Exception("Cannot open unloaded form");
        }

        $codeEditor = $this->makeCodeEditor();
        $designer = $this->makeDesigner();

        $tabs = new UXTabPane();
        $tabs->side = 'LEFT';
        $tabs->tabClosingPolicy = 'UNAVAILABLE';

        $codeTab = new UXTab();
        $codeTab->text = 'Исходный код';
        $codeTab->content = $codeEditor;
        $codeTab->style = '-fx-cursor: hand;';
        $codeTab->graphic = Ide::get()->getImage($this->codeEditor->getIcon());
        $codeTab->tooltip = UXTooltip::of($this->codeFile);

        $designerTab = new UXTab();
        $designerTab->text = 'Дизайн';
        $designerTab->content = $designer;
        $designerTab->style = '-fx-cursor: hand;';
        $designerTab->graphic = Ide::get()->getImage($this->getIcon());

        $this->designerTab = $designerTab;

        $tabs->tabs->add($this->designerTab);

        $this->codeTab = $codeTab;

        if (File::of($this->codeFile)->exists()) {
            $codeTab->on('change', function () use ($tabs) {
                UXApplication::runLater(function () use ($tabs) {
                    if ($tabs->selectedTab === $this->codeTab) {
                        $this->switchToSmallSource();
                        $tabs->selectedTab = $this->designerTab;
                    }
                });
            });

            $tabs->tabs->add($this->codeTab);
        }

        $this->tabs = $tabs;

        return $this->tabs;
    }

    public function switchToSource()
    {
        $this->tabs->selectTab($this->codeTab);
    }

    public function switchToSmallSource()
    {
        static $dividerPositions;

        $data = Ide::get()->getUserConfigValue(__CLASS__ . ".dividerPositions");

        if ($data) {
            $dividerPositions = Flow::of(Str::split($data, ','))->map(function ($el) {
                return (double) Str::trim($el);
            })->toArray();
        }

        $this->switchToDesigner();

        $count = $this->viewerAndEvents->items->count();

        if ($count > 1) {
            $dividerPositions = $this->viewerAndEvents->dividerPositions;

            $item = $this->viewerAndEvents->items[$count - 1];
            $this->viewerAndEvents->items->remove($item);
        }

        $panel = new UXAnchorPane();

        $content = $this->codeTab->content;
        UXAnchorPane::setAnchor($content, 0);

        $this->viewerAndEvents->items->add($panel);
        $panel->add($content);

        if ($dividerPositions) {
            $this->viewerAndEvents->dividerPositions = $dividerPositions;
        }

        $class = __CLASS__;

        $panel->watch('height', function () use ($class) {
            if ($this->viewerAndEvents->items->count() > 1) {
                Ide::get()->setUserConfigValue("$class.dividerPositions", Str::join($this->viewerAndEvents->dividerPositions, ','));
            }
        });
    }

    public function switchToDesigner($hideSource = false)
    {
        $this->tabs->selectTab($this->designerTab);

        if ($hideSource && $this->viewerAndEvents->items->count() > 1) {
            $this->codeTab->content = $this->viewerAndEvents->items[1];
            unset($this->viewerAndEvents->items[1]);
        }
    }

    protected function makeCodeEditor()
    {
        return $this->codeEditor->makeUi();
    }

    protected function makeDesigner($fullArea = false)
    {
        $area = new UXAnchorPane();

        $viewer = new UXScrollPane($area);

        if (!$fullArea) {
            $designPane = new UXDesignPane();
            $designPane->size = $this->layout->size;
            $designPane->position = [10, 10];
            $designPane->add($this->layout);

            UXAnchorPane::setAnchor($this->layout, 0);
        } else {
            $this->layout->style = '-fx-border-width: 1px; -fx-border-style: dashed; -fx-border-color: silver;';
            $this->layout->position = [10, 10];
            $area->add($this->layout);
        }

        $this->designer = new UXDesigner($this->layout);
        $this->designer->onAreaMouseDown([$this, '_onAreaMouseDown']);
        $this->designer->onNodeClick([$this, '_onNodeClick']);
        $this->designer->onNodePick([$this, '_onNodePick']);
        $this->designer->onChanged([$this, '_onChanged']);

        foreach ($this->layout->children as $node) {
            if ($node instanceof UXData) {
                continue;
            }

            $this->designer->registerNode($node);
        }

        if (!$fullArea) {
            $area->add($designPane);
        }

        $this->elementTypePane = new FormElementTypePane($this->format->getFormElements());

        $designerCodeEditor = new UXAnchorPane();
        $designerCodeEditor->hide();

        $this->designerCodeEditor = $designerCodeEditor;

        $this->viewerAndEvents = new UXSplitPane([$viewer, $this->designerCodeEditor]);
        $this->viewerAndEvents->orientation = 'VERTICAL';

        $this->viewerAndEvents->items->remove($designerCodeEditor);

        $split = new UXSplitPane([$this->viewerAndEvents, $this->elementTypePane->getContent()]);

        $this->makeContextMenu();

        return $split;
    }

    protected function makeContextMenu()
    {
        $this->contextMenu = new ContextMenu($this, $this->format->getContextCommands());
        $this->designer->contextMenu = $this->contextMenu->getRoot();
    }

    protected function _onAreaMouseDown(UXMouseEvent $e)
    {
        $selected = $this->elementTypePane->getSelected();

        $this->save();

        if ($selected) {
            $node = $selected->createElement();

            if (!$node->id) {
                $n = 3;

                $id = Str::format($selected->getIdPattern(), "");

                if ($this->layout->lookup("#$id")) {
                    $id = Str::format($selected->getIdPattern(), "Alt");

                    if ($this->layout->lookup("#$id")) {
                        do {
                            $id = Str::format($selected->getIdPattern(), $n);
                            $n++;
                        } while ($this->layout->lookup("#$id"));
                    }
                }

                $node->id = $id;
            }

            $size = $selected->getDefaultSize();
            $position = [$e->x, $e->y];

            $snapSize = $this->designer->snapSize;

            if ($this->designer->snapEnabled) {
                $size[0] = floor($size[0] / $snapSize) * $snapSize;
                $size[1] = floor($size[1] / $snapSize) * $snapSize;

                $position[0] = floor($position[0] / $snapSize) * $snapSize;
                $position[1] = floor($position[1] / $snapSize) * $snapSize;
            }

            $node->size = $size;
            $node->position = $position;

            $this->layout->add($node);
            $this->designer->registerNode($node);

            if (!$e->controlDown) {
                $this->elementTypePane->clearSelected();
            }

            $data = DataUtils::get($node);

            foreach ($selected->getInitProperties() as $key => $property) {
                if ($property['virtual']) {
                    $data->set($key, $property['value']);
                } else if ($key !== 'width' && $key !== 'height') {
                    $node->{$key} = $property['value'];
                }
            }
        } else {
            $this->updateProperties($this);
        }
    }

    protected function _onChanged()
    {
        $this->save();
        $this->_onNodePick();
    }

    protected function _onNodePick()
    {
        $node = $this->designer->pickedNode;

        if ($node) {
            Timer::run(50, function () use ($node) {
                $this->updateProperties($node);
            });
        }
    }

    protected function _onNodeClick(UXMouseEvent $e)
    {
        $selected = $this->elementTypePane->getSelected();

        if ($selected) {
            $this->designer->unselectAll();
            $this->elementTypePane->clearSelected();
            return true;
        }
    }

    public static function initializeElement(AbstractFormElement $element)
    {
        $properties = new UXDesignProperties();
        $element->createProperties($properties);

        return static::$typeProperties[get_class($element)] = $properties;
    }

    protected function updateEventTypes($node, $selected = null)
    {
        $this->eventManager->load();

        $element = $this->format->getFormElement($node);

        /** @var MainForm $mainForm */
        $mainForm = Ide::get()->getMainForm();
        $pane = $mainForm->getPropertiesPane();

        /** @var UXTabPane $tabs */
        $tabs = $pane->lookup('#tabs');

        $properties = $element ? static::$typeProperties[get_class($element)] : null;

        if ($properties) {
            $properties->target = $element->getTarget($node);
            $properties->update();
        }

        $eventTab = $tabs ? $tabs->tabs[1] : null;

        if (!$selected && $eventTab && $eventTab->content) {
            $list = $eventTab->content->lookup('#list');

            if ($list instanceof UXListView) {
                $selected = Items::first($list->selectedItems);

                if ($selected) {
                    $selected = $selected['type']['code'];
                }
            }
        }

        if ($eventTab) {
            $eventTab->content = $this->makeEventTypePane($node, $element, $selected);
        }
    }

    protected function updateProperties($node)
    {
        $this->eventManager->load();
        $element = $this->format->getFormElement($node);

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

        $properties = $element ? static::$typeProperties[get_class($element)] : null;

        if ($properties) {
            $properties->target = $element->getTarget($node);
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

        $eventTypes = $element ? $element->getEventTypes() : [];

        if ($eventTypes) {
            $eventTab = new UXTab();
            $eventTab->text = 'События';
            $eventTab->closable = false;

            $tabs->tabs->add($eventTab);
        }

        if ($tabs->tabs && $node) {
            $pane->add($tabs);

            if ($selectedIndex > -1) {
                $tabs->selectedIndex = $selectedIndex;
            }
        }

        if ($eventTypes && $node) {
            $this->updateEventTypes($node, $selectedEvent);
        }

        if (!$properties || !$properties->getGroupPanes()) {
            $hint = new UXLabel('Список пуст.');

            if ($node === null) {
                $hint->text = '...';
            }

            $hint->style = '-fx-font-style: italic;';
            $hint->maxSize = [10000, 10000];
            $hint->padding = 20;
            $hint->alignment = 'BASELINE_CENTER';

            $pane->children->add($hint);
        }
    }

    public function jumpToEventSource($node, $eventType)
    {
        $bind = $this->eventManager->findBind($node->id, $eventType);

        if ($bind) {
            $this->switchToSmallSource();
            //$this->codeEditor->setEditableArea($bind['beginLine'], $bind['endLine']);
            Timer::run(100, function () use ($bind) {
                $this->codeEditor->jumpToLine($bind['beginLine'], $bind['beginPosition']);
            });
        }
    }

    protected function makeEventTypePane($node, AbstractFormElement $element, $selected = null)
    {
        $addButton = new UXButton("Добавить событие");
        $addButton->height = 30;
        $addButton->maxWidth = 10000;
        $addButton->style = '-fx-font-weight: bold;';
        $addButton->graphic = Ide::get()->getImage('icons/plus16.png');


        $eventTypes = $element->getEventTypes();

        $addButton->on('action', function (UXEvent $event) use ($node, $eventTypes) {
            $menu = new UXContextMenu();

            foreach ($eventTypes as $type) {
                $menuItem = new UXMenuItem($type['name'], Ide::get()->getImage($type['icon']));
                $menuItem->on('action', function () use ($node, $type) {
                    $this->switchToSmallSource();
                    $this->eventManager->addBind($node->id, $type['code'], $type['kind']);

                    Timer::run(100, function () use ($node, $type) {
                        $this->codeEditor->load();
                        $this->updateEventTypes($node, $type['code']);

                        $this->jumpToEventSource($node, $type['code']);
                    });
                });

                if ($this->eventManager->findBind($node->id, $type['code'])) {
                    $menuItem->disable = true;
                }

                $menu->items->add($menuItem);
            }

            /** @var UXButton $target */
            $target = $event->target;
            $menu->show(Ide::get()->getMainForm(), $target->screenX + 100, $target->screenY + $target->height);
        });

        $deleteButton = new UXButton();
        $deleteButton->size = [25, 25];
        $deleteButton->graphic = Ide::get()->getImage('icons/delete16.png');

        $changeButton = new UXButton();
        $changeButton->size = [25, 25];
        $changeButton->graphic = Ide::get()->getImage('icons/exchange16.png');
        $changeButton->enabled = false;

        $editButton = new UXButton("Редактировать");
        $editButton->graphic = Ide::get()->getImage('icons/edit16.png');
        $editButton->height = 25;
        $editButton->maxWidth = 10000;
        UXHBox::setHgrow($editButton, 'ALWAYS');

        $otherButtons = new UXHBox([$deleteButton, $changeButton, $editButton]);
        $otherButtons->spacing = 3;
        $otherButtons->height = 25;
        $otherButtons->maxWidth = 10000;

        $actions = new UXVBox([$addButton, $otherButtons]);
        $actions->fillWidth = true;
        $actions->spacing = 4;
        $actions->padding = 4;
        $actions->leftAnchor = 0;
        $actions->rightAnchor = 0;

        $pane = new UXAnchorPane();

        $list = new UXListView();
        UXAnchorPane::setAnchor($list, 2);
        $list->topAnchor = 68;
        $list->id = 'list';

        $deleteButton->on('action', function () use ($list, $node) {
            $selected = Items::first($list->selectedItems);

            if ($selected) {
                if ($bind = $this->eventManager->removeBind($node->id, $selected['type']['code'])) {

                    Timer::run(100, function () use ($bind) {
                        $this->codeEditor->load();
                        $this->codeEditor->jumpToLine($bind['eventLine'] - 1);
                    });
                }
                $this->updateEventTypes($node);
            }
        });

        $editButton->on('action', function () use ($list, $node) {
            $selected = Items::first($list->selectedItems);

            if (!$selected && $list->items->count()) {
                $list->selectedIndexes = [0];
                $selected = $list->items[0];
            }

            if ($selected) {
                $this->jumpToEventSource($node, $selected['type']['code']);
            }
        });

        $list->on('click', function (UXMouseEvent $e) use ($list, $node) {
            if ($e->clickCount > 1) {
                $selected = Items::first($list->selectedItems);

                if ($selected) {
                    $this->jumpToEventSource($node, $selected['type']['code']);
                }
            }
        });

        $list->setCellFactory(function (UXListCell $cell, $item, $empty) {
            if ($item) {
                /** @var array $eventType */
                $eventType = $item['type'];
                $methodName = $item['info']['methodName'];

                $cell->text = null;

                $nameLabel = new UXLabel($eventType['name']);
                $nameLabel->css('font-weight', 'bold');

                $methodNameLabel = new UXLabel($methodName);
                $methodNameLabel->textColor = UXColor::of('gray');

                $namesBox = new UXVBox([$nameLabel, $methodNameLabel]);

                $icon = Ide::get()->getImage($eventType['icon']);

                if ($icon) {
                    $box = new UXHBox([$icon, $namesBox]);
                    $box->spacing = 8;
                    $box->alignment = 'CENTER_LEFT';
                } else {
                    $box = $namesBox;
                }

                $box->padding = [3, 3];

                $cell->graphic = $box;
            }
        });

        if ($node) {
            $binds = $this->eventManager->findBinds($node->id);

            foreach ($binds as $code => $info) {
                if ($eventType = $eventTypes[$code]) {
                    $list->items->add([
                        'type' => $eventType,
                        'info' => $info
                    ]);
                }
            }

            if ($selected) {
                foreach ($list->items as $i => $item) {
                    if ($item['type']['code'] == $selected) {
                        $list->selectedIndexes = [$i];
                        break;
                    }
                }
            }
        }

        $pane->add($actions);
        $pane->add($list);

        return $pane;
    }
}