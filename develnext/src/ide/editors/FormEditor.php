<?php
namespace ide\editors;

use ide\action\ActionEditor;
use ide\editors\form\FormElementTypePane;
use ide\editors\menu\ContextMenu;
use ide\formats\AbstractFormFormat;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\event\AbstractEventKind;
use ide\formats\form\FormEventManager;
use ide\formats\FormFormat;
use ide\formats\PhpCodeFormat;
use ide\forms\ActionConstructorForm;
use ide\forms\MainForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\ProjectFile;
use ide\utils\FileUtils;
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
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXList;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXLoader;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXParent;
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
use php\util\Regex;

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

    /** @var  UXHBox */
    protected $modulesPane;

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
     * @var ActionEditor
     */
    protected $actionEditor;

    /**
     * @var string
     */
    protected $tabOpened = null;

    /**
     * @var FormEventManager
     */
    protected $eventManager;

    /**
     * @var null
     */
    protected $selectedClass = null;

    /**
     * @var null
     */
    protected $selectedMethod = null;

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

        $this->actionEditor = new ActionEditor($phpFile . '.axml');
        $this->actionEditor->makeUi();

        $this->codeEditor = Ide::get()->getRegisteredFormat(PhpCodeFormat::class)->createEditor($phpFile);
        $this->codeEditor->register(AbstractCommand::make('Скрыть', 'icons/close16.png', function () {
            $this->codeEditor->save();
            $this->switchToDesigner(true);
        }));
        $this->codeEditor->register(AbstractCommand::make('Поменять расположение', 'icons/layoutHorizontal16.png', function () {
            $this->viewerAndEvents->orientation = $this->viewerAndEvents->orientation == 'VERTICAL' ? 'HORIZONTAL' : 'VERTICAL';
        }));

        $this->codeEditor->register(AbstractCommand::makeSeparator());

        $this->codeEditor->registerDefaultCommands();
        $this->codeEditor->register(new SetDefaultCommand($this, 'php'));

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

        $this->actionEditor->load();

        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->load();
        }

        if (File::of($this->configFile)->exists()) {
            $this->config->load($this->configFile);
        }
    }

    public function checkNodeId($newId)
    {
        return (Regex::match('^[A-Za-z\\_]{1}[A-Za-z0-9\\_]{1,60}$', $newId));
    }

    public function changeNodeId($node, $newId)
    {
        if (!$this->checkNodeId($newId)) {
            return 'invalid';
        }

        if ($node->id == $newId) {
            return '';
        }

        if ($this->layout->lookup("#$newId")) {
            return 'busy';
        }

        $data = DataUtils::get($node, $this->layout, false);

        if ($data) {
            $data->id = "data-$newId";
        }

        $this->eventManager->renameBind($node->id, $newId);
        $this->codeEditor->load();

        $node->id = $newId;
        return '';
    }

    public function deleteNode($node)
    {
        $designer = $this->designer;

        $element = $this->format->getFormElement($node);

        if ($element && $element->isLayout()) {
            foreach ($element->getLayoutChildren($node) as $sub) {
                $this->deleteNode($sub);
            }
        }

        $designer->unselectNode($node);
        $designer->unregisterNode($node);

        DataUtils::remove($node);
        $node->parent->remove($node);

        if ($this->eventManager->removeBinds($this->getNodeId($node))) {
            $this->codeEditor->load();
        }
    }

    public function save()
    {
        $this->formDumper->save($this);

        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->save();
        }

        $this->actionEditor->save();

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
        //$this->designer->unselectAll();

        $this->eventManager->load();
        $this->updateProperties($this->designer->pickedNode ?: $this);

        UXApplication::runLater(function () {
            $this->designer->requestFocus();
        });
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

        if (Ide::get()->getUserConfigValue(__CLASS__ . '.sourceEditorEx', false)) {
            UXApplication::runLater(function () {
                $this->switchToSmallSource();
            });
        }

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

        $panel->add($content);

        if ($dividerPositions) {
            $this->viewerAndEvents->dividerPositions = $dividerPositions;
        }

        $class = __CLASS__;

        $func = function () use ($class) {
            if ($this->viewerAndEvents->items->count() > 1) {
                Ide::get()->setUserConfigValue("$class.dividerPositions", Str::join($this->viewerAndEvents->dividerPositions, ','));
            }
        };

        if ($content instanceof UXTabPane) {
            $tabs = $content;
        } else {
            $tabs = new UXTabPane();
            $tabs->side = 'LEFT';

            $codeTab = new UXTab();
            $codeTab->text = 'PHP Код';
            $codeTab->content = $panel;
            $codeTab->closable = false;
            $codeTab->graphic = ico('script16');
            $codeTab->style = '-fx-cursor: hand';

            $constructorTab = new UXTab();
            $constructorTab->text = 'Конструктор';
            $constructorTab->closable = false;
            $constructorTab->graphic = ico('wizard16');
            $constructorTab->style = '-fx-cursor: hand';

            $constructorTab->on('change', function () use ($tabs, $codeTab) {
                UXApplication::runLater(function() use ($tabs, $codeTab) {
                    $tabs->selectedTab = $codeTab;
                });

                if (!$this->selectedClass || !$this->selectedMethod) {
                    UXDialog::show('Выберите событие или добавьте новое перед запуском конструктора событий');
                    return;
                }

                $actionConstructor = new ActionConstructorForm();
                $actionConstructor->showAndWait($this->actionEditor, $this->selectedClass, $this->selectedMethod);
            });

            $panel = new UXAnchorPane();
            $panel->visible = false;
            $panel->add($this->actionEditor->getPane());
            UXAnchorPane::setAnchor($this->actionEditor->getPane(), 0);

            $constructorTab->content = $panel;

            $tabs->tabs->addAll([$codeTab]);
        }

        $this->viewerAndEvents->items->add($tabs);

        $tabs->watch('width', $func);
        $tabs->watch('height', $func);

        Ide::get()->setUserConfigValue("$class.sourceEditorEx", true);
    }

    public function switchToDesigner($hideSource = false)
    {
        $this->tabs->selectTab($this->designerTab);

        if ($hideSource && $this->viewerAndEvents->items->count() > 1) {
            $class = __CLASS__;

            Ide::get()->setUserConfigValue("$class.sourceEditorEx", false);
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
        $this->layout->classes->add('form-editor');

        $viewer = new UXScrollPane($area);

        $viewer->on('click', function ($e) {
            $this->designer->unselectAll();
            $this->_onAreaMouseDown($e);
        });

        if (!$fullArea) {
            $designPane = new UXDesignPane();
            $designPane->size = $this->layout->size;
            $designPane->position = [10, 10];
            $designPane->add($this->layout);

            UXAnchorPane::setAnchor($this->layout, 0);
        } else {
            $this->layout->style = '-fx-border-width: 1px; -fx-border-style: none; -fx-border-color: silver;';
            $this->layout->position = [10, 10];
            $area->add($this->layout);
        }

        $this->designer = new UXDesigner($this->layout);
        $this->designer->onAreaMouseDown(function ($e) { $this->_onAreaMouseDown($e); } );
        $this->designer->onNodeClick([$this, '_onNodeClick']);
        $this->designer->onNodePick(function () {
            $this->_onNodePick();
        });

        $this->designer->onChanged([$this, '_onChanged']);

        $registerChildren = function ($children) use (&$registerChildren) {
            /** @var UXNode $node */
            foreach ($children as $node) {
                if ($node instanceof UXData) {
                    continue;
                }

                if (!$node->classes->has('ignore')) {
                    $element = $this->format->getFormElement($node);

                    if ($element) {
                        $element->registerNode($node);
                    }

                    if ($element->isLayout()) {
                        $registerChildren($element->getLayoutChildren($node));
                    }

                    $this->designer->registerNode($node);
                }
            }
        };

        $registerChildren($this->layout->children);



        if (!$fullArea) {
            $area->add($designPane);
        }

        $this->elementTypePane = new FormElementTypePane($this->format->getFormElements());

        $designerCodeEditor = new UXAnchorPane();
        $designerCodeEditor->hide();

        $this->designerCodeEditor = $designerCodeEditor;

        $class = __CLASS__;

        $this->viewerAndEvents = new UXSplitPane([$viewer, $this->designerCodeEditor]);

        try {
            $this->viewerAndEvents->orientation = Ide::get()->getUserConfigValue("$class.orientation", 'VERTICAL');
        } catch (\Exception $e) {
            $this->viewerAndEvents->orientation = 'VERTICAL';
        }

        $this->viewerAndEvents->watch('orientation', function () use ($class) {
            UXApplication::runLater(function () use ($class) {
                Ide::get()->setUserConfigValue("$class.orientation", $this->viewerAndEvents->orientation);
            });
        });

        $this->viewerAndEvents->items->remove($designerCodeEditor);

        $scrollPane = new UXScrollPane($this->elementTypePane->getContent());
        $scrollPane->fitToWidth = true;
        $scrollPane->maxWidth = $scrollPane->content->maxWidth;

        $split = new UXSplitPane([$this->viewerAndEvents, $scrollPane]);

        $this->makeContextMenu();

        return $split;
    }

    protected function makeContextMenu()
    {
        $this->contextMenu = new ContextMenu($this, $this->format->getContextCommands());
        $this->designer->contextMenu = $this->contextMenu->getRoot();
    }

    public function generateNodeId(AbstractFormElement $element)
    {
        $n = 3;

        $id = Str::format($element->getIdPattern(), "");

        if ($this->layout->lookup("#$id")) {
            $id = Str::format($element->getIdPattern(), "Alt");

            if ($this->layout->lookup("#$id")) {
                do {
                    $id = Str::format($element->getIdPattern(), $n);
                    $n++;
                } while ($this->layout->lookup("#$id"));
            }
        }

        return $id;
    }

    protected function createElement(AbstractFormElement $element, $screenX, $screenY, $parent = null)
    {
        $node = $element->createElement();

        if (!$node->id) {
            $node->id = $this->generateNodeId($element);
        }

        $size = $element->getDefaultSize();
        $position = [$screenX, $screenY];

        $snapSize = $this->designer->snapSize;

        if ($this->designer->snapEnabled) {
            $size[0] = floor($size[0] / $snapSize) * $snapSize;
            $size[1] = floor($size[1] / $snapSize) * $snapSize;

            $position[0] = floor($position[0] / $snapSize) * $snapSize;
            $position[1] = floor($position[1] / $snapSize) * $snapSize;
        }

        $node->size = $size;

        if ($parent) {
            $parentElement = $this->format->getFormElement($parent);
            $parentElement->addToLayout($parent, $node, $screenX, $screenY);
        } else {
            $position = $this->layout->screenToLocal($screenX, $screenY);
            $node->position = $position;
            $this->layout->add($node);
        }

        $element = $this->format->getFormElement($node);

        if ($element) {
            $element->registerNode($node);
        }

        $this->designer->registerNode($node);

        $data = DataUtils::get($node);

        foreach ($element->getInitProperties() as $key => $property) {
            if ($property['virtual']) {
                $data->set($key, $property['value']);
            } else if ($key !== 'width' && $key !== 'height') {
                $node->{$key} = $property['value'];
            }
        }

        return $node;
    }

    protected function _onAreaMouseDown(UXMouseEvent $e)
    {
        $selected = $this->elementTypePane->getSelected();

        $this->save();

        if ($selected) {
            $node = $this->createElement($selected, $e->screenX, $e->screenY);

            if (!$e->controlDown) {
                $this->elementTypePane->clearSelected();
            }

            $this->designer->requestFocus();

            UXApplication::runLater(function () use ($node) {
                $this->designer->unselectAll();
                $this->designer->selectNode($node);
            });
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

        $this->layout->requestFocus();

        if ($selected) {
            $element = $this->format->getFormElement($e->sender);

            if ($element && $element->isLayout()) {
                $node = $this->createElement($selected, $e->screenX, $e->screenY, $e->sender);

                if (!$e->controlDown) {
                    $this->elementTypePane->clearSelected();
                }

                $this->designer->requestFocus();

                UXApplication::runLater(function () use ($node) {
                    $this->designer->unselectAll();
                    $this->designer->selectNode($node);
                });
            }

            //$this->designer->unselectAll();
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
                    $selected = $selected['eventCode'];
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

            $propTab->content = new UXScrollPane($propTab->content);
            $propTab->content->fitToWidth = true;

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
        $bind = $this->eventManager->findBind($this->getNodeId($node), $eventType);

        if ($bind) {
            $this->switchToSmallSource();
            //$this->codeEditor->setEditableArea($bind['beginLine'], $bind['endLine']);
            Timer::run(100, function () use ($bind) {
                $this->codeEditor->jumpToLine($bind['beginLine'], $bind['beginPosition']);
            });
        }
    }

    public function getNodeId($node)
    {
        return $node->id;
    }

    public function setDefaultEventEditor($editor)
    {
        Ide::get()->setUserConfigValue(__CLASS__ . '.editorOnDoubleClick', $editor);
    }

    public function getDefaultEventEditor($request = true)
    {
        $editorType = Ide::get()->getUserConfigValue(__CLASS__ . '.editorOnDoubleClick');

        if ($request && !$editorType) {
            $buttons = ['constructor' => 'Конструктор', 'php' => 'PHP редактор'];

            $dialog = new MessageBoxForm('Какой использовать редактор для редактирования событий?', $buttons);

            Ide::get()->getMainForm()->toast('Используйте "Конструктор" если вы новичок!');

            if ($dialog->showDialogWithFlag()) {
                $editorType = $dialog->getResult();

                if ($dialog->isChecked()) {
                    Ide::get()->setUserConfigValue(__CLASS__ . '.editorOnDoubleClick', $editorType);
                }
            }
        }

        return $editorType;
    }

    public function openEventSource($node, $eventCode, $editorType = null)
    {
        if (!$editorType) {
            $editorType = $this->getDefaultEventEditor();
        }

        $element = $this->format->getFormElement($node);
        $eventTypes = $element->getEventTypes();
        $eventType = null;

        $code = $eventCode;

        list($eventCode, $eventParam) = Str::split($eventCode, '-', 2);

        foreach ($eventTypes as $el) {
            if (Str::equalsIgnoreCase($el['code'], $eventCode)) {
                $eventType = $el;
            }
        }

        switch ($editorType) {
            case 'php':
                $this->jumpToEventSource($node, $code);
                break;
            case 'constructor':
                $bind = $this->eventManager->findBind($this->getNodeId($node), $code);

                if ($bind) {
                    $selectedClass = FileUtils::stripExtension(File::of($this->file)->getName());
                    $selectedMethod = $bind['methodName'];

                    $actionConstructor = new ActionConstructorForm();

                    if ($eventType) {
                        $nodeId = $this->getNodeId($node);

                        $actionConstructor->title = 'Событие - ' . $eventType['name'];

                        if ($eventParam) {
                            $actionConstructor->title .= ' (' . $eventType['kind']->findParamName($eventParam) . ')';
                        }

                        if ($nodeId) {
                            $actionConstructor->title .= ' - id = "' . $nodeId . '"';
                        }
                    } else {
                        $actionConstructor->title = "Метод - $selectedMethod()";
                    }

                    $actionConstructor->showAndWait($this->actionEditor, $selectedClass, $selectedMethod);
                } else {

                }

                break;
        }
    }

    protected function makeEventContextMenu(UXListView $list, $node)
    {
        $menu = new ContextMenu();

        $menu->addCommand(AbstractCommand::makeWithText('Открыть в конструкторе', 'icons/wizard16.png', function () use ($list, $node) {
            $selected = Items::first($list->selectedItems);

            if ($selected) {
                $this->openEventSource($node, $selected['eventCode'], 'constructor ');
            }
        }));

        $menu->addSeparator();

        $menu->addCommand(AbstractCommand::makeWithText('Открыть в php-редакторе', 'icons/phpFile16.png', function () use ($list, $node) {
            $selected = Items::first($list->selectedItems);

            if ($selected) {
                $this->openEventSource($node, $selected['eventCode'], 'php');
            }
        }));

        return $menu;
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
            $prevKind = null;

            foreach ($eventTypes as $type) {
                $menuItem = new UXMenuItem($type['name'], Ide::get()->getImage($type['icon']));

                /** @var AbstractEventKind $kind */
                $kind = $type['kind'];

                $variants = $kind->getParamVariants();

                if (!$variants) {
                    $menuItem->on('action', function () use ($node, $type) {
                        $this->switchToSmallSource();
                        $this->eventManager->addBind($this->getNodeId($node), $type['code'], $type['kind']);

                        Timer::run(100, function () use ($node, $type) {
                            $this->codeEditor->load();
                            $this->updateEventTypes($node, $type['code']);

                            $this->openEventSource($node, $type['code']);
                        });
                    });

                    if ($this->eventManager->findBind($this->getNodeId($node), $type['code'])) {
                        $menuItem->disable = true;
                    }
                } else {
                    $menuItem = new UXMenu($menuItem->text, $menuItem->graphic);
                }

                if ($prevKind && $prevKind != $type['kind']) {
                    $menu->items->add(UXMenuItem::createSeparator());
                }

                $menu->items->add($menuItem);

                if ($variants) {
                    $appendVariants = function ($variants, UXMenu $menuItem) use ($node, $type, &$appendVariants) {
                        foreach ($variants as $name => $param) {
                            if ($param === '-') {
                                $menuItem->items->add(UXMenuItem::createSeparator());
                                continue;
                            }

                            if (is_array($param)) {
                                $subItem = new UXMenu($name);
                                $menuItem->items->add($subItem);

                                $appendVariants($param, $subItem);
                                continue;
                            }

                            $code = $type['code'];

                            if ($param) {
                                $code .= "-$param";
                            }

                            $item = new UXMenuItem($name);
                            $item->on('action', function () use ($node, $type, $code) {
                                $this->switchToSmallSource();

                                $this->eventManager->addBind($this->getNodeId($node), $code, $type['kind']);

                                Timer::run(100, function () use ($node, $type, $code) {
                                    $this->codeEditor->load();
                                    $this->updateEventTypes($node, $code);

                                    $this->openEventSource($node, $code);
                                });
                            });

                            if ($this->eventManager->findBind($this->getNodeId($node), $code)) {
                                $item->disable = true;
                            }

                            $menuItem->items->add($item);
                        }
                    };

                    $appendVariants($variants, $menuItem);
                }

                $prevKind = $type['kind'];
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
                if ($bind = $this->eventManager->removeBind($this->getNodeId($node), $selected['eventCode'])) {
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
                $this->openEventSource($node, $selected['eventCode']);
            }
        });

        $list->on('click', function (UXMouseEvent $e) use ($list, $node) {
            if ($e->clickCount > 1) {
                $selected = Items::first($list->selectedItems);

                if ($selected) {
                    $this->openEventSource($node, $selected['eventCode']);
                }
            }
        });


        $eventContextMenu = $this->makeEventContextMenu($list, $node);

        $list->setCellFactory(function (UXListCell $cell, $item, $empty) use ($list, $deleteButton, $eventContextMenu) {
            if ($item) {
                /** @var array $eventType */
                $eventType = $item['type'];
                $methodName = $item['info']['methodName'];
                $param = $item['paramName'];

                $cell->text = null;

                $constructorLink = new UXHyperlink('Изменить');
                $constructorLink->style = '-fx-text-fill: gray';
                $constructorLink->on('action', function ($event) use ($list, $item, $deleteButton, $constructorLink, $eventContextMenu) {
                    foreach ($list->items as $i => $el) {
                        if ($el === $item) {
                            UXApplication::runLater(function () use ($list, $i) {
                                $list->selectedIndex = $i;
                                $list->focusedIndex = $i;
                            });
                            break;
                        }
                    }

                    $eventContextMenu->getRoot()->showByNode($constructorLink, - 120, $constructorLink->height);
                });

                $name = new UXLabel($eventType['name']);
                $name->css('font-weight', 'bold');

                $nameLabel = new UXHBox([$name]);
                $nameLabel->spacing = 4;


                $methodNameLabel = new UXLabel($methodName);
                $methodNameLabel->textColor = UXColor::of('gray');

                if ($param) {
                    $paramLabel = new UXLabel("($param)");
                    $paramLabel->style = '-fx-font-style: italic';
                    $paramLabel->textColor = UXColor::of('#2f6eb2');
                    $paramLabel->paddingRight = 3;

                    $line = new UXHBox([$paramLabel, $constructorLink]);
                } else {
                    $line = new UXHBox([$constructorLink]);
                }

                $namesBox = new UXVBox([$nameLabel, $line]);

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
            $binds = $this->eventManager->findBinds($this->getNodeId($node));

            foreach ($binds as $code => $info) {
                list($code, $param) = Str::split($code, '-');

                if ($eventType = $eventTypes[$code]) {
                    $list->items->add([
                        'type' => $eventType,
                        'info' => $info,
                        'param' => $param,
                        'paramName' => $eventType['kind']->findParamName($param),
                        'eventCode' => $param ? "$eventType[code]-$param" : $eventType['code'],
                    ]);
                }
            }

            if ($selected) {
                foreach ($list->items as $i => $item) {
                    if ($item['eventCode'] == $selected) {
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