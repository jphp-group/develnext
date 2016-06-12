<?php
namespace ide\editors;

use develnext\lexer\inspector\entry\ArgumentEntry;
use develnext\lexer\inspector\entry\ExtendTypeEntry;
use develnext\lexer\inspector\entry\MethodEntry;
use develnext\lexer\inspector\entry\TypeEntry;
use develnext\lexer\inspector\entry\TypePropertyEntry;
use ide\action\ActionEditor;
use ide\behaviour\AbstractBehaviourSpec;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\common\ObjectListEditorItem;
use ide\editors\form\IdeActionsPane;
use ide\editors\form\IdeBehaviourPane;
use ide\editors\form\FormElementTypePane;
use ide\editors\form\IdeEventListPane;
use ide\editors\form\IdeFormFactory;
use ide\editors\form\IdeObjectTreeList;
use ide\editors\form\IdePropertiesPane;
use ide\editors\form\IdeTabPane;
use ide\editors\menu\ContextMenu;
use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\DoubleArrayPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\formats\AbstractFormFormat;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\SourceEventManager;
use ide\formats\FormFormat;
use ide\formats\GuiFormFormat;
use ide\formats\PhpCodeFormat;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\marker\target\MarkerTargable;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\ProjectFile;
use ide\project\ProjectIndexer;
use ide\systems\FileSystem;
use ide\ui\Notifications;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDesignPane;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\DataUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXCustomNode;
use php\gui\UXData;
use php\gui\UXNode;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTooltip;
use php\io\File;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lang\IllegalStateException;
use php\lib\arr;
use php\lib\fs;
use php\lib\Items;
use php\lib\reflect;
use php\lib\Str;
use php\lib\String;
use php\time\Time;
use php\util\Configuration;
use php\util\Flow;
use php\util\Regex;
use php\util\SharedStack;
use timer\AccurateTimer;

/**
 * Class FormEditor
 * @package ide\editors
 *
 * @property AbstractFormFormat $format
 */
class FormEditor extends AbstractModuleEditor implements MarkerTargable
{
    const BORDER_SIZE = 8;

    use EventHandlerBehaviour;

    protected $designerCodeEditor;

    /**
     * @var IdePropertiesPane
     */
    protected $propertiesPane;

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
     * @var UXScrollPane
     */
    protected $elementTypePaneContainer;

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
     * @var IdeBehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var string
     */
    protected $tabOpened = null;

    /**
     * @var SourceEventManager
     */
    protected $eventManager;

    /**
     * @var ScriptModuleEditor[]
     */
    protected $modules = [];

    /**
     * @var UXDesignProperties[]
     */
    protected static $typeProperties = [];

    protected $opened;

    /**
     * @var UXNode
     */
    protected $codeEditorUi;

    /**
     * @var IdeEventListPane
     */
    protected $eventListPane;

    /**
     * @var IdeBehaviourPane
     */
    protected $behaviourPane;

    /**
     * @var IdeObjectTreeList
     */
    protected $objectTreeList;

    /**
     * @var UXNode
     */
    protected $markerNode;

    /**
     * @var IdeActionsPane
     */
    protected $actionsPane;

    /**
     * @var IdeFormFactory
     */
    protected $factory;

    /**
     * @var FormElementTypePane
     */
    protected $prototypeTypePane;

    /**
     * @var bool
     */
    protected $loaded = false;

    public function __construct($file, AbstractFormDumper $dumper)
    {
        parent::__construct($file);

        $this->config = new Configuration();
        $this->formDumper = $dumper;

        $phpFile = $file;
        $confFile = $file;

        if (Str::endsWith($phpFile, '.fxml')) {
            $this->factory = new IdeFormFactory(fs::nameNoExt($file), $this->file);

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

        $this->eventManager = new SourceEventManager($phpFile);

        $this->codeFile = $phpFile;
        $this->configFile = $confFile;

        $this->initCodeEditor($this->codeFile);

        $this->actionEditor = new ActionEditor($phpFile . '.axml');
        $this->actionEditor->setFormEditor($this);

        $this->behaviourManager = new IdeBehaviourManager(fs::pathNoExt($phpFile) . '.behaviour', function ($targetId) {
            $node = $targetId ? $this->layout->lookup("#$targetId") : $this;

            if (!$node) {
                return null;
            }

            return $this->getFormat()->getFormElement($node);
        });
    }

    protected function initCodeEditor($phpFile)
    {
        $this->codeEditor = Ide::get()->getRegisteredFormat(PhpCodeFormat::class)->createEditor($phpFile);
        $this->codeEditor->register(AbstractCommand::make('Скрыть', 'icons/close16.png', function () {
            $this->codeEditor->save();

            Ide::get()->setUserConfigValue(get_class($this) . ".multipleEditor", false);
            $this->switchToDesigner(true);
        }));

       /* $this->codeEditor->register(AbstractCommand::make('На весь экран', 'icons/fullScreenEnable16.png', function () {
            $fullScreen = Ide::get()->getUserConfigValue(get_class($this) . ".codeEditorFullScreen", true);

            Ide::get()->setUserConfigValue(get_class($this) . ".codeEditorFullScreen", !$fullScreen);

            if ($this->tabs->selectedTab === $this->codeTab) {
                $this->switchToDesigner();

                uiLater(function () {
                    $this->switchToSmallSource();
                });
            } else {
                $this->switchToDesigner(true);

                uiLater(function () {
                    $this->switchToSource();
                });
            }
        }));*/

        $this->codeEditor->register(AbstractCommand::make('Совместное редактирование', 'icons/layoutHorizontal16.png', function () {
            Ide::get()->setUserConfigValue(get_class($this) . ".multipleEditor", true);

            if ($this->tabs->selectedTab === $this->codeTab) {
                $this->switchToDesigner();

                uiLater(function () {
                    $this->switchToSmallSource();
                });
            } else {
                uiLater(function () {
                    $this->viewerAndEvents->orientation = $this->viewerAndEvents->orientation == 'VERTICAL' ? 'HORIZONTAL' : 'VERTICAL';
                });
            }
        }));

        $this->codeEditor->register(AbstractCommand::makeSeparator());

        $this->codeEditor->registerDefaultCommands();
        $this->codeEditor->register(new SetDefaultCommand($this, 'php'));

        $this->codeEditor->on('update', function () {
            if ($this->opened) {
                $node = $this->designer->pickedNode;
                $this->codeEditor->save();

                $this->updateEventTypes($node ? $node : $this);
            }
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
     * @return IdeBehaviourManager
     */
    public function getBehaviourManager()
    {
        return $this->behaviourManager;
    }

    protected function loadOthers()
    {
        $this->actionEditor->load();

        if (!$this->behaviourManager->load()) {
            Notifications::warningFileOccurs($this->behaviourManager->getFile());
        }

        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->load();
        }
    }

    public function refreshInspectorType()
    {
        if ($project = Ide::project()) {
            $type = new TypeEntry();
            $type->fulledName = "app\\forms\\" . $this->getTitle();

            foreach ($this->getModules() as $name) {
                $name = "mixin:app\\modules\\$name";
                $type->extends[str::lower($name)] = $e = new ExtendTypeEntry($name, ['weak' => true, 'public' => true]);
            }

            foreach ($this->getObjectList() as $el) {
                $type->properties[$el->value] = $prop = new TypePropertyEntry();
                $prop->name = $el->value;
                $prop->data['content']['DEF'] = $el->element ? $el->element->getName() : '';
                $prop->data['icon'] = $el->getIcon();
                $prop->data['type'][] = $el->element->getElementClass() ?: UXNode::class;

                $behaviourType = new TypeEntry();
                $behaviourType->data['getters'] = true;

                foreach ($this->behaviourManager->getBehaviours($el->value) as $one) {
                    $behaviourType->properties[$one->getCode()] = $t = new TypePropertyEntry();
                    $t->name = $one->getCode();
                    $t->data['icon'] = $this->behaviourManager->getBehaviourSpec($one)->getIcon();
                    $t->data['type'][] = reflect::typeOf($one);
                }

                $prop->data['type'][] = $behaviourType;
            }

            foreach ($project->getInspectors() as $inspector) {
                $inspector->putDynamicType($type);
            }

            $type = new TypeEntry();
            $type->fulledName = "mixin:app\\forms\\" . $this->getTitle();

            foreach ($this->getObjectList() as $el) {
                $type->properties[$el->value] = $prop = new TypePropertyEntry();
                $prop->name = $el->value;
                $prop->data['icon'] = $el->getIcon();
                $prop->data['type'][] = $el->element->getElementClass() ?: UXNode::class;
            }

            foreach ($project->getInspectors() as $inspector) {
                $inspector->putDynamicType($type);
            }
        }
    }

    /**
     *
     */
    public function load()
    {
        $this->trigger('load:before');

        $this->loaded = true;

        if ($this->factory) {
            $this->factory->reload();
        }

        $this->eventManager->load();
        $this->formDumper->load($this);

        $this->loadOthers();

        if (File::of($this->configFile)->exists()) {
            $this->config->load($this->configFile);
        }

        if ($this->config->get('form.backgroundColor')) {
            $this->layout->backgroundColor = UXColor::of($this->config->get('form.backgroundColor'));
        }

        $this->refreshInspectorType();

        $this->trigger('load:after');

        return true;
    }

    protected function saveOthers()
    {
        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->save();
        }

        $this->actionEditor->save();
        $this->behaviourManager->save();

        if ($this->actionsPane && $this->getIdeConfig()) {
            $this->getIdeConfig()->put($this->actionsPane->getConfig());
        }

        $blockedNodes = [];

        foreach ($this->designer->getNodes() as $node) {
            if ($this->designer->getNodeLock($node)) {
                $id = $this->getNodeId($node);
                $blockedNodes[$id] = $id;
            }
        }

        if ($this->getIdeConfig()) {
            $this->getIdeConfig()->set('blockedNodes', $blockedNodes);

            $this->saveIdeConfig();
        }
    }

    public function save()
    {
        $this->formDumper->save($this);

        $this->saveOthers();

        Stream::tryAccess($this->configFile, function (Stream $stream) {
            $this->config->save($stream);
        }, 'w+');

        if ($this->factory) {
            $this->factory->reload();
        }
    }

    public function close($save = true)
    {
        parent::close($save);

        $this->opened = false;

        if (FileSystem::getOpened() === $this) {
            $this->updateProperties(null);
        }
    }

    public function createClone($id)
    {
        if (str::contains($id, '.')) {
            $gui = GuiFrameworkProjectBehaviour::get();

            if ($gui) {
                list($factoryName, $factoryId) = str::split($id, '.');

                $formEditor = $gui->getFormEditor($factoryName);

                if ($formEditor) {
                    return $formEditor->createClone($factoryId);
                }
            }

            return null;
        }

        $clone = $this->factory->create($id);

        if ($clone) {
            $clone->id = "";
            $clone->data('-factory-version', $this->getObjectVersion($id));
        }

        return $clone;
    }

    public function updateClonesForNewType($oldType, $newType)
    {
        $count = 0;

        Logger::info("UpdateClonesForNewType '$oldType' to '$newType' ...");

        if (!$this->loaded) {
            $this->formDumper->load($this);
        }

        DataUtils::scanAll($this->layout, function (UXData $data = null, UXNode $node) use ($oldType, $newType, &$count) {
            if ($node instanceof UXCustomNode) {
                if ($node->get('type') == $oldType) {
                    $node->set('type', $newType);
                    $count++;
                }
            } else {
                $factoryId = $node->data('-factory-id');

                if ($factoryId && $factoryId == $oldType) {
                    $node->data('-factory-id', $newType);
                    $count++;
                }
            }
        });

        if ($count > 0) {
            $this->formDumper->save($this);
        }

        return $count;
    }

    public function reloadClones()
    {
        if ($this->factory) {
            $this->factory->reload();
        }

        Logger::trace();

        $gui = GuiFrameworkProjectBehaviour::get();

        if ($gui) {
            $freeNodes = new SharedStack();

            DataUtils::scanAll($this->layout, function (UXData $data = null, UXNode $node) use ($gui, $freeNodes) {
                if ($node instanceof UXCustomNode) {
                    $freeNodes->push($node);
                } else {
                    $factoryId = $node->data('-factory-id');

                    if ($factoryId) {
                        $freeNodes->push($node);
                    }
                }
            });

            Logger::trace('Reload Clones scanned ...');

            $factories = [];

            $clones = [];

            foreach ($freeNodes as $node) {
                if ($node instanceof UXCustomNode) {
                    $type = $node->get('type');
                    list($factoryName, $factoryId) = str::split($type, '.');

                    $formEditor = $factories[$factoryName] ?: $gui->getFormEditor($factoryName);
                    $factories[$factoryName] = $formEditor;

                    if ($formEditor) {
                        $clone = $formEditor->createClone($factoryId);

                        if ($clone) {
                            $clone->x = $node->get('x');
                            $clone->y = $node->get('y');

                            $this->designer->unregisterNode($node);
                            $clones[] = [$node, $clone, $node->parent];
                        }
                    }
                } elseif ($node instanceof UXNode) {
                    $factoryId = $node->data('-factory-id');

                    if ($factoryId) {
                        list($factoryName, $factoryId) = str::split($factoryId, '.');

                        $formEditor = $factories[$factoryName] ?: $gui->getFormEditor($factoryName);
                        $factories[$factoryName] = $formEditor;

                        if ($formEditor) {
                            $factoryVersion = $node->data('-factory-version');

                            if ($formEditor->getObjectVersion($factoryId) == $factoryVersion) {
                                continue;
                            }

                            $clone = $formEditor->createClone($factoryId);

                            if ($clone) {
                                $clone->position = $node->position;

                                if ($this->designer->isSelectedNode($node)) {
                                    $this->designer->unselectNode($node);

                                    uiLater(function () use ($clone) {
                                        $this->designer->selectNode($clone);
                                    });
                                }

                                $this->designer->unregisterNode($node);

                                $clones[] = [$node, $clone, $node->parent];
                            } else {
                                $this->getDesigner()->unselectNode($node);
                            }
                        }
                    }
                }
            }

            foreach ($clones as list($node, $clone, $parent)) {
                $parent->children->replace($node, $clone);
                $this->registerNode($clone);
                //$this->refreshNode($it[0]);
            }
        }

        Logger::trace('Reload Clones finished.');
    }

    public function open()
    {
        parent::open();

        $this->reloadClones();

        $ideConfig = $this->getIdeConfig();

        if ($this->actionsPane) {
            $this->actionsPane->setConfig($ideConfig->toArray());
        }

        $blockedNodes = $ideConfig->getArray('blockedNodes');
        $blockedNodes = array_combine($blockedNodes, $blockedNodes);

        foreach ($this->designer->getNodes() as $node) {
            if ($blockedNodes[$this->getNodeId($node)]) {
                $this->designer->setNodeLock($node, true);
            } else {
                $this->designer->setNodeLock($node, false);
            }
        }

        $this->elementTypePane->resetConfigurable(get_class($this));
        $this->elementTypePane->setElements($this->format->getFormElements());

        if ($this->prototypeTypePane) {
            $gui = GuiFrameworkProjectBehaviour::get();
            $this->prototypeTypePane->resetConfigurable(get_class($this) . "#prototype");

            if ($gui) {
                $this->prototypeTypePane->setElements($gui->getAllPrototypes($this));
            }
        }

        $this->designer->disabled = false;
        $this->opened = true;
        //$this->designer->unselectAll();

        $this->eventManager->load();

        $this->refresh();
        $this->leftPaneUi->refresh();
        $this->leftPaneUi->refreshObjectTreeList();

        $this->updateMultipleEditor();

        UXApplication::runLater(function () {
            $this->updateProperties($this->designer->pickedNode ?: $this);
            //$this->updateEventTypes($this->designer->pickedNode ?: $this);

            UXApplication::runLater(function () {
                $this->designer->requestFocus();
            });
        });
    }

    public function hide()
    {
        parent::hide();

        $this->save();
        $this->designer->disabled = true;
        $this->reindex();
    }

    public function refreshNode(UXNode $node)
    {
        $element = $this->format->getFormElement($node);

        if ($element) {
            $element->refreshNode($node);
        }

        $targetId = $this->getNodeId($node);

        if ($targetId) {
            $behaviours = $this->behaviourManager->getBehaviours($targetId);

            foreach ($behaviours as $one) {
                $spec = $this->behaviourManager->getBehaviourSpec($one);

                if ($spec) {
                    $spec->refreshNode($node, $one);
                }
            }
        } else {
            if ($factoryId = $node->data('-factory-id')) {
                $gui = GuiFrameworkProjectBehaviour::get();

                if ($gui) {
                    $prototype = $gui->getPrototype($factoryId);

                    if ($prototype) {
                        foreach ($prototype['behaviours'] as $one) {
                            /** @var AbstractBehaviourSpec $spec */
                            $spec = $one['spec'];

                            if ($spec instanceof AbstractBehaviourSpec) {
                                $spec->refreshNode($node, $one['value']);
                            }
                        }
                    }
                }
            }
        }
    }

    public function refresh()
    {
        Logger::info("Start refresh");

        $this->eachNode(function (UXNode $node, $nodeId, AbstractFormElement $element = null) {
            if ($element) {
                $this->refreshNode($node);
            }
        });

        Logger::info("Finish refresh");
    }

    public function registerNode(UXNode $uiNode)
    {
        $element = $this->format->getFormElement($uiNode);

        if ($element) {
            if ($new = $element->registerNode($uiNode)) {
                $uiNode = $new;
            }
        }

        $this->designer->registerNode($uiNode);

        $this->designer->setNodeSimple($uiNode, $uiNode->data('-factory-id'));

        return $uiNode;
    }

    public function getRefactorRenameNodeType()
    {
        return GuiFormFormat::REFACTOR_ELEMENT_ID_TYPE;
    }

    protected function reindexImpl(ProjectIndexer $indexer)
    {
        if (!$this->layout) {
            $this->formDumper->load($this);
        }

        $nodes = $this->findNodesToRegister($this->layout->children);

        $result = [];

        $indexer->remove($this->file, '_objects');

        $index = [];

        /** @var UXNode $node */
        foreach ($nodes as $node) {
            $element = $this->format->getFormElement($node);

            $index[$this->getNodeId($node)] = [
                'id' => $this->getNodeId($node),
                'type' => get_class($element),
                'version' => (int) $node->data('-factory-version'),
                'data' => $element->getIndexData($node),
            ];
        }

        $indexer->set($this->file, '_objects', $index);

        $this->refreshInspectorType();

        return $result;
    }


    public function checkNodeId($newId)
    {
        return (Regex::match('^[A-Za-z\\_]{1}[A-Za-z0-9\\_]{1,60}$', $newId));
    }

    public function changeNodeId($node, $newId)
    {
        Logger::info("ChangeNodeId '{$node->id}' to '$newId'");

        if (!$this->checkNodeId($newId)) {
            return 'invalid';
        }

        if ($node->id == $newId) {
            return '';
        }

        if ($this->layout->lookup("#$newId")) {
            return 'busy';
        }

        //  TODO Refactor it.
        $class = new \ReflectionClass(AbstractForm::class);

        $props = [];
        foreach ($class->getProperties() as $prop) {
            if (!$prop->isStatic()) {
                $props[str::lower($prop->getName())] = $prop;
            }
        }

        if ($props[str::lower($newId)]) {
            return 'busy';
        }
        // -----------------

        $element = $this->format->getFormElement($node);
        $eventsWithIdParam = [];

        if ($element) {
            foreach ($element->getEventTypes() as $it) {
                if ($it['idParameter']) {
                    $eventsWithIdParam[] = $it['code'];
                }
            }
        }

        $data = DataUtils::get($node, $this->layout, false);

        if ($data) {
            $data->id = "data-$newId";
        }

        $this->behaviourManager->changeTargetId($node->id, $newId);

        $binds = $this->eventManager->renameBind($node->id, $newId, $eventsWithIdParam);

        foreach ($binds as $bind) {
            $this->actionEditor->renameMethod($bind['className'], $bind['methodName'], $bind['newMethodName']);
        }

        $this->codeEditor->load();
        $node->id = $newId;

        $this->reindex();

        $this->leftPaneUi->updateEventList($newId);
        $this->leftPaneUi->updateBehaviours($newId);
        $this->leftPaneUi->refreshObjectTreeList($newId);
        return '';
    }


    public function getObjectVersion($id)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $index = (array) $project->getIndexer()->get($this->file, '_objects');

            return (int) $index[$id]['version'];
        }

        return -1;
    }

    /**
     * @deprecated TODO use GuiFrameworkProjectBehaviour::getObjectListOfForm()
     * @return ObjectListEditorItem[]
     */
    public function getObjectList()
    {
        return GuiFrameworkProjectBehaviour::get()->getObjectList($this->file);
    }

    public function deleteNode($node)
    {
        $designer = $this->designer;

        $element = $this->format->getFormElement($node);

        if ($element && $element->isLayout()) {
            Logger::debug('Delete children of layout - ' . get_class($node));
            foreach ($element->getLayoutChildren($node) as $sub) {
                $this->deleteNode($sub);
            }
        }

        $designer->unselectNode($node);
        $designer->unregisterNode($node);

        $nodeId = $this->getNodeId($node);

        if ($nodeId && $node->parent) {
            DataUtils::remove($node);
        }

        if ($node->parent) {
            $node->parent->remove($node);
        }

        if ($nodeId) {
            $binds = $this->eventManager->findBinds($nodeId);

            foreach ($binds as $bind) {
                $this->actionEditor->removeMethod($bind['className'], $bind['methodName']);
            }

            if ($this->eventManager->removeBinds($nodeId)) {
                $this->codeEditor->load();
            }

            foreach ($this->behaviourManager->getBehaviours($nodeId) as $one) {
                $spec = $this->behaviourManager->getBehaviourSpec($one);

                if ($spec) {
                    $spec->deleteNode($node, $one);
                }
            }

            $this->behaviourManager->removeBehaviours($nodeId);
            $this->behaviourManager->save();
        }

        $this->leftPaneUi->refreshObjectTreeList();
        $this->reindex();
    }

    public function selectForm()
    {
        $this->designer->unselectAll();

        $this->updateProperties($this);
    }

    public function selectObject($targetId)
    {
        $node = $this->layout->lookup("#$targetId");

        $this->designer->unselectAll();

        if ($node) {
            $this->designer->selectNode($node);

            waitAsync(50, function () use ($node) {
                $this->updateProperties($node);
            });
        }
    }

    protected function makeActionsUi()
    {
        $this->actionsPane = $ui = new IdeActionsPane($this->designer);

        $ui->on('change', function () {
            $this->save();
        });

        return $ui;
    }

    public function makeUi()
    {
        if (!$this->layout) {
            throw new \Exception("Cannot open unloaded form");
        }

        $this->codeEditorUi = $codeEditor = $this->makeCodeEditor();
        $designer = $this->makeDesigner();

        $tabs = new UXTabPane();
        $tabs->side = 'LEFT';
        $tabs->tabClosingPolicy = 'UNAVAILABLE';

        $codeTab = new UXTab();
        $codeTab->text = 'Исходный код';
        $codeTab->content = $this->codeEditorUi;
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
            $codeTab->on('change', function (UXEvent $e) use ($tabs) {
                uiLater(function () use ($tabs) {
                    if ($tabs->selectedTab === $this->codeTab) {
                        if ($this->viewerAndEvents->items[1] != null) {
                            $this->hideCodeEditorInDesigner();

                            /*TimerScript::executeAfter(2000, function () {
                                $this->switchToSource();
                            });  */
                        }
                    }

                    if ($tabs->selectedTab === $this->designerTab) {
                        $this->updateMultipleEditor();
                    }
                });
            });

            $tabs->tabs->add($this->codeTab);
        }

        $this->tabs = $tabs;

        /*if (Ide::get()->getUserConfigValue(__CLASS__ . '.sourceEditorEx', false)) {
            UXApplication::runLater(function () {
                $this->switchToSmallSource();
            });
        }*/

        return $this->tabs;
    }

    public function switchToSource()
    {
        $this->tabs->selectTab($this->codeTab);
    }

    public function switchToFullSource()
    {
        Logger::info("Start switch to full source editor...");

        $count = $this->viewerAndEvents->items->count();

        if ($count > 1) {
            $item = $this->viewerAndEvents->items[$count - 1];
            $this->viewerAndEvents->items->remove($item);

            Logger::info(".. reset small code editor");
        }

        $this->codeTab->content = $this->codeEditorUi;
    }

    public function switchToSmallSource()
    {
        /*$this->switchToSource();
        return;*/

        static $dividerPositions;

        Logger::info("Start switch to small source editor...");

        $data = Ide::get()->getUserConfigValue(__CLASS__ . ".dividerPositions");

        if ($data) {
            $dividerPositions = Flow::of(Str::split($data, ','))->map(function ($el) {
                return (double) Str::trim($el);
            })->toArray();
        }

        /*UXApplication::runLater(function () {
            $this->switchToDesigner();
            $this->codeEditor->requestFocus();
        });*/


        $class = __CLASS__;

        $count = $this->viewerAndEvents->items->count();

        if ($count < 2) {
            $panel = new UXAnchorPane();

            if ($dividerPositions) {
                $this->viewerAndEvents->dividerPositions = $dividerPositions;
            }

            $func = function () use ($class) {
                UXApplication::runLater(function () use ($class) {
                    if ($this->viewerAndEvents->items->count() > 1) {
                        Ide::get()->setUserConfigValue("$class.dividerPositions", Str::join($this->viewerAndEvents->dividerPositions, ','));
                    }
                });
            };

            $this->codeTab->content = null;
            $this->viewerAndEvents->items->add($panel);

            $panel->observer('width')->addListener($func);
            $panel->observer('height')->addListener($func);

            AccurateTimer::executeAfter(100, function () use ($panel) {
                $content = $this->codeEditorUi;
                UXAnchorPane::setAnchor($content, 0);

                $panel->add($content);
                $this->codeEditor->requestFocus();
            });
        }

        Ide::get()->setUserConfigValue("$class.sourceEditorEx", true);

        Logger::info("Finish switching of small source editor");
    }

    public function getModules()
    {
        $modules = $this->config->get('modules');

        $modules = Str::split($modules, '|');

        $result = [];

        foreach ($modules as &$module) {
            $module = Str::trim($module);
            $result[$module] = $module;
        }

        return $result;
    }

    public function getModuleEditors()
    {
        $modules = $this->getModules();

        foreach ($this->modules as $name => $value) {
            if (!$modules[$name]) {
                unset($this->modules[$name]);
            }
        }

        foreach ($modules as $module) {
            $module = Str::trim($module);
            $this->modules[$module] = FileSystem::fetchEditor(Ide::get()->getOpenedProject()->getFile(GuiFrameworkProjectBehaviour::SCRIPTS_DIRECTORY . "/$module"), true);
        }

        return $this->modules;
    }

    public function hideCodeEditorInDesigner()
    {
        if ($this->viewerAndEvents->items->count() > 1) {
            $content = $this->viewerAndEvents->items[1];

            $this->viewerAndEvents->items->removeByIndex(1);

            AccurateTimer::executeAfter(100, function () use ($content) {
                $this->codeTab->content = $content;
            });
        }
    }

    public function updateMultipleEditor()
    {
        if ($this->tabs->selectedTab === $this->designerTab) {
            $multipleEditor = Ide::get()->getUserConfigValue(get_class($this) . ".multipleEditor", false);

            if ($multipleEditor) {
                if ($this->viewerAndEvents->items[1] == null) {
                    $this->switchToSmallSource();
                }
            } else {
                if ($this->viewerAndEvents->items[1] != null) {
                    $this->switchToDesigner(true);
                }
            }
        }
    }

    public function switchToDesigner($hideSource = false)
    {
        $this->tabs->selectTab($this->designerTab);

        if ($hideSource) {
            $this->hideCodeEditorInDesigner();
        }
    }

    protected function makeCodeEditor()
    {
        return $this->codeEditor->makeUi();
    }

    /**
     * @param callable $callback (UXNode $node, AbstractFormElement $element, int $level)
     * @param array $children
     */
    public function eachNode(callable $callback, array $children = null)
    {
        $func = function ($nodes, $level = 0) use ($callback, &$func) {
            foreach ($nodes as $node) {
                if ($node instanceof UXData || $node->classes->has('ignore')) {
                    continue;
                }

                $nodeId = $this->getNodeId($node);

                /*if (!$nodeId) {
                    continue;
                }*/

                $element = $this->format->getFormElement($node);

                $callback($node, $nodeId, $element, $level);

                if ($element && $element->isLayout()) {
                    $func($element->getLayoutChildren($node), $level + 1);
                }
            }
        };

        $func($children !== null ? $children : $this->layout->children);
    }

    protected function findNodesToRegister($nodes)
    {
        $result = [];

        $registerChildren = function ($children, &$result) use (&$registerChildren) {
            /** @var UXNode $node */
            foreach ($children as $node) {
                if (!$node) {
                    continue;
                }

                if ($node instanceof UXData) {
                    continue;
                }

                $targetId = $this->getNodeId($node);

                if (!$targetId) {
                    continue;
                }

                if (!$node->classes->has('ignore')) {
                    $element = $this->format->getFormElement($node);

                    if ($element && $node->id) {
                        if ($new = $element->registerNode($node)) {
                            $node = $new;
                        }
                    }

                    if ($element && $element->isLayout()) {
                        $registerChildren($element->getLayoutChildren($node), $result);
                    }

                    $result[] = $node;
                }
            }
        };

        $registerChildren($nodes, $result);
        return $result;
    }

    protected function makePrototypePane()
    {
        $prototypeTypePane = new FormElementTypePane([], true, $this->elementTypePane->getToggleGroup());
        $prototypeTypePane->applyConfigure(get_class($this) . "#prototype");

        return $prototypeTypePane;
    }

    protected function makeDesigner($fullArea = false)
    {
        $area = new UXAnchorPane();
        $area->classes->add('FormEditor');

        $viewer = new UXScrollPane($area);

        $viewer->on('mouseUp', function ($e) {
            $this->selectForm();
        });

        if (!$fullArea) {
            $designPane = new UXDesignPane();
            $designPane->size = $this->layout->size;
            $designPane->position = [10, 10];
            $designPane->onResize(function () {
                $this->designer->update();
            });

            $this->markerNode = $designPane;
            $designPane->add($this->layout);

            $this->trigger('makeDesignPane', [$designPane]);
            UXAnchorPane::setAnchor($this->layout, 0);
        } else {
            $this->markerNode = $this->layout;

            $this->layout->style = '-fx-border-width: 1px; -fx-border-style: none; -fx-border-color: silver;';
            $this->layout->position = [10, 10];
            $area->add($this->layout);
        }

        $this->designer = new UXDesigner($this->layout);
        $this->designer->onAreaMouseUp(function ($e) { $this->_onAreaMouseUp($e); } );
        $this->designer->onNodeClick(function ($e) {
            $this->_onNodeClick($e);
        });
        $this->designer->onNodePick(function () {
            $this->_onNodePick();
        });

        $this->designer->onChanged([$this, '_onChanged']);

        $this->designer->addSelectionControl($area);

        foreach ($this->findNodesToRegister($this->layout->children) as $node) {
            $this->designer->registerNode($node);
        }

        if (!$fullArea) {
            $area->add($designPane);
        }

        $this->elementTypePane = new FormElementTypePane($this->format->getFormElements());
        $this->elementTypePane->applyConfigure(get_class($this));

        $this->prototypeTypePane = $this->makePrototypePane();
        //$this->behaviourPane = new IdeBehaviourPane($this->behaviourManager);

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
        $this->elementTypePaneContainer = $scrollPane;

        if ($this->prototypeTypePane) {
            $typePanes = new UXTabPane();
            $typePanes->tabClosingPolicy = 'UNAVAILABLE';
            $typePanes->side = 'RIGHT';

            $elementTab = new UXTab();
            $elementTab->text = 'Объекты';
            $elementTab->content = $scrollPane;

            $typePanes->tabs->add($elementTab);

            $prototypeTab = new UXTab();
            $prototypeTab->text = 'Прототипы';
            $prototypeTab->content = new UXScrollPane($this->prototypeTypePane->getContent());
            $prototypeTab->content->fitToWidth = true;

            $typePanes->tabs->add($prototypeTab);
            $typePanes->maxWidth = $scrollPane->content->maxWidth;

            $scrollPane = $typePanes;
        }

        UXSplitPane::setResizeWithParent($scrollPane, false);

        $actions = $this->makeActionsUi();

        if ($actions) {
            $wrap = new UXVBox([$actions, $this->viewerAndEvents]);
            UXVBox::setVgrow($this->viewerAndEvents, 'ALWAYS');

            $split = new UXSplitPane([$wrap, $scrollPane]);
        } else {
            $split = new UXSplitPane([$this->viewerAndEvents, $scrollPane]);
        }

        $this->makeContextMenu();

        return $split;
    }

    protected function makeContextMenu()
    {
        $this->contextMenu = new ContextMenu($this, $this->format->getContextCommands());
        $this->contextMenu->setFilter(function ()  {
            return $this->layout->focused || $this->contextMenu->getRoot()->visible || $this->layout->findFocusedNode();
        });

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

    /**
     * @param AbstractFormElement $element
     * @param $screenX
     * @param $screenY
     * @param null $parent
     * @return mixed|UXNode
     * @throws \php\lang\IllegalArgumentException
     */
    protected function createElement($element, $screenX, $screenY, $parent = null)
    {
        Logger::info("Create element: element = " . get_class($element) . ", screenX = $screenX, screenY = $screenY, parent = $parent");

        $isClone = false;

        if ($element instanceof ObjectListEditorItem) {
            $isClone = true;
            $node = $this->createClone($element->value);

            $size = $node->size;
        } else {
            $node = $element->createElement();

            if (!$node->id) {
                $node->id = $this->generateNodeId($element);
            }

            $size = $element->getDefaultSize();
        }

        $selectionRectangle = $this->designer->getSelectionRectangle();

        if ($parent == null && $selectionRectangle->width >= 8 && $selectionRectangle->height >= 8) {
            $size = $selectionRectangle->size;
            $selectionRectangle->size = [1, 1];
        }

        $snapSizeX = $this->designer->snapSizeX;
        $snapSizeY = $this->designer->snapSizeY;

        if ($this->designer->snapEnabled) {
            if (!$isClone) {
                $size[0] = floor($size[0] / $snapSizeX) * $snapSizeX;
                $size[1] = floor($size[1] / $snapSizeY) * $snapSizeY;
            }
        }

        if (!$isClone) {
            $node->size = $size;
        }

        if ($parent) {
            $parentElement = $this->format->getFormElement($parent);
            $parentElement->addToLayout($parent, $node, $screenX, $screenY);
        } else {
            $position = $this->layout->screenToLocal($screenX, $screenY);

            $position[0] = floor($position[0] / $snapSizeX) * $snapSizeX;
            $position[1] = floor($position[1] / $snapSizeY) * $snapSizeY;

            $node->position = $position;
            $this->layout->add($node);
        }

        $element = $this->format->getFormElement($node);

        $node = $this->registerNode($node);

        if (!$isClone) {
            $data = DataUtils::get($node);

            if ($element) {
                foreach ($element->getInitProperties() as $key => $property) {
                    if ($property['virtual']) {
                        $data->set($key, $property['value']);
                    } else if ($key !== 'width' && $key !== 'height') {
                        $node->{$key} = $property['value'];
                    }
                }

                $initialBehaviours = $element->getInitialBehaviours();

                if ($initialBehaviours) {
                    foreach ($initialBehaviours as $spec) {
                        $behaviour = $spec->createBehaviour();
                        $this->behaviourManager->apply($node->id, $behaviour);
                    }

                    $this->behaviourManager->save();
                }
            }
        }

        $this->refreshNode($node);

        if (!$isClone) {
            $this->reindex();
            $this->leftPaneUi->refreshObjectTreeList($this->getNodeId($node));
        }

        return $node;
    }

    protected function _onAreaMouseUp(UXMouseEvent $e = null)
    {
        $selected = $this->elementTypePane->getSelected();

        $this->save();

        if ($selected) {
            $selectionRectangle = $this->designer->getSelectionRectangle();

            $node = $this->createElement($selected, $selectionRectangle->x, $selectionRectangle->y, null, !$e->controlDown);

            if ($e && !$e->controlDown) {
                Logger::debug("Clear selection from element type pane");
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

        uiLater(function () {
            $this->layout->requestFocus();
        });
    }

    public function addUseImports(array $imports)
    {
        $this->eventManager->addUseImports($imports);

        waitAsync(100, function () {
            $this->codeEditor->load();
        });
    }

    public function insertCodeToMethod($class, $method, $code)
    {
        $this->eventManager->insertCodeToMethod($class, $method, $code);

        waitAsync(100, function () {
            $this->codeEditor->load();
        });
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
            uiLater(function () use ($node) {
                $this->updateProperties($node);
            });
        }
    }

    protected function _onNodeClick(UXMouseEvent $e)
    {
        $node = $e->target;

        /*if ($node && $node->data('-factory-id')) {
            return false;
        }*/

        $selected = $this->elementTypePane->getSelected();

        $this->layout->requestFocus();

        if ($selected) {
            $element = $this->format->getFormElement($e->sender);

            if ($element) {
                $node = $this->createElement($selected, $e->screenX, $e->screenY, $element->isLayout() ? $e->sender : null, !$e->controlDown);

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

    public static function fetchElementProperties(AbstractFormElement $element)
    {
        if ($result = static::$typeProperties[get_class($element)]) {
            return $result;
        }

        return self::initializeElement($element);
    }

    public static function initializeElement(AbstractFormElement $element)
    {
        Logger::info("Initialize element = " . get_class($element));

        $properties = new UXDesignProperties();
        $element->createProperties($properties);

        if (!$properties->getGroupPanes()) {
            Logger::warn("Properties is empty for element = " . get_class($element));
        }

        return static::$typeProperties[get_class($element)] = $properties;
    }

    public function makeLeftPaneUi()
    {
        $ui = new IdeTabPane();

        $objectTreeList = new IdeObjectTreeList();
        $objectTreeList->setTraverseFunc([$this, 'eachNode']);
        $objectTreeList->setLevelOffset(1);
        $objectTreeList->setEmptyItem(new ObjectListEditorItem(
            $this->getTitle(), Ide::get()->getImage($this->getIcon()), '', 0
        ));
        $objectTreeList->on('change', function ($targetId) {
            if ($targetId) {
                $this->selectObject($targetId);
            } else {
                $this->selectForm();
            }
        });

        $this->objectTreeList = $objectTreeList;
        $ui->addObjectTreeList($objectTreeList);

        $this->propertiesPane = new IdePropertiesPane();
        $ui->addPropertiesPane($this->propertiesPane);

        $this->eventListPane = new IdeEventListPane($this->eventManager);
        $this->eventListPane->setCodeEditor($this->codeEditor);
        $this->eventListPane->setActionEditor($this->actionEditor);
        $this->eventListPane->setContextEditor($this);
        $this->eventListPane->on('edit', function ($eventCode, $editor) {
            if ($editor == 'php') {
                $this->switchToSmallSource();
            }
        });

        $ui->addEventListPane($this->eventListPane);

        $this->behaviourPane = new IdeBehaviourPane($this->behaviourManager);
        $this->behaviourPane->on('remove', function ($targetId, AbstractBehaviourSpec $spec) {
            $node = $this->layout->lookup("#$targetId");

            if ($node instanceof UXNode) {
                $behaviour = $this->behaviourManager->getBehaviourByTargetId($targetId, $spec->getType());

                if ($behaviour) {
                    $spec->deleteSelf($node, $behaviour);
                    $this->reindex();
                } else {
                    Logger::warn("Unable to call deleteSelf() of behaviour spec class, behaviour not found");
                }
            } else {
                Logger::warn("Unable to call deleteSelf() of behaviour spec class, node not found, targetId = $targetId");
            }
        });
        $this->behaviourPane->on('add', function () {
            $this->reindex();
        });

        $ui->addBehaviourPane($this->behaviourPane);

        $ui->on('change', function ($targetId) {
            $node = $this->layout->lookup("#$targetId");

            if ($node instanceof UXNode) {
                $node->data('-factory-version', $version = $node->data('-factory-version') + 1);
               // Logger::debug("Change object factory version '$targetId', set version = $version");
            }
        });

        return $ui;
    }

    protected function updateEventTypes($node, $selected = null)
    {
        if ($this->eventManager) {
            $this->eventManager->load();
            $this->eventListPane->update($this->getNodeId($node));
        }
    }

    protected function updateProperties($node)
    {
        if ($node instanceof UXNode) {
            $factoryId = $node->data('-factory-id');
        } else {
            $factoryId = null;
        }

        if ($factoryId) {
            $this->leftPaneUi->hideBehaviourPane();
            $this->leftPaneUi->hideEventListPane();

            $properties = new UXDesignProperties();
            $properties->addGroup('prototype', 'Клон');
            $properties->addGroup('general', 'Главное');

            $editor = new SimpleTextPropertyEditor(function () use ($factoryId) {
                return $factoryId;
            }, function () {});
            $editor->setReadOnly(true);
            $properties->addProperty('prototype', 'factoryId', 'Прототип', $editor);
            $properties->addProperty('general', 'position', 'Позиция (X, Y)', new DoubleArrayPropertyEditor());

            $editor = new BooleanPropertyEditor();
            $editor->setAsDataProperty(null, true);
            $properties->addProperty('general', 'disabled', 'Отключеный', $editor);

            $editor = new BooleanPropertyEditor();
            $editor->setAsDataProperty(null, true);
            $properties->addProperty('general', 'hidden', 'Скрытый', $editor);

            if ($this->propertiesPane) {
                $this->propertiesPane->clearProperties();
            }

            $this->trigger('updateNode:before', [$node, $properties]);

            if ($this->propertiesPane) {
                $this->propertiesPane->addProperties($properties);
            }
        } else {
            $this->leftPaneUi->showEventListPane();
            $this->leftPaneUi->showBehaviourPane();
            if ($this->eventManager) {
                $this->eventManager->load();
            }

            $element = $this->format->getFormElement($node);
            $properties = $element ? static::fetchElementProperties($element) : null;

            if ($this->propertiesPane) {
                $this->propertiesPane->clearProperties();
            }

            $this->trigger('updateNode:before', [$node, $properties]);

            if ($this->propertiesPane) {
                $this->propertiesPane->addProperties($properties);
            }

            if ($this->eventListPane) {
                $this->eventListPane->setEventTypes($element ? $element->getEventTypes() : []);
            }
        }

        $this->trigger('updateNode:after', [$node, $properties]);

        $this->leftPaneUi->update($this->getNodeId($node), $element ? $element->getTarget($node) : $node);
    }

    public function jumpToClassMethod($class, $method)
    {
        $coord = $this->eventManager->findMethod($class, $method);

        Logger::info("Jump to class method $class::$method()");

        if ($coord) {
            $this->switchToSmallSource();

            waitAsync(100, function () use ($coord) {
                $this->codeEditor->jumpToLine($coord['line'], $coord['pos']);
            });
        }
    }

    public function jumpToEventSource($node, $eventType)
    {
        $bind = $this->eventManager->findBind($this->getNodeId($node), $eventType);

        Logger::info("Jump to event source node = {$this->getNodeId($node)}, eventType = $eventType");

        if ($bind) {
            $this->switchToSmallSource();

            waitAsync(100, function () use ($bind) {
                $this->codeEditor->jumpToLine($bind['beginLine'], $bind['beginPosition']);
            });
        }
    }

    public function getNodeId($node)
    {
        return $node ? $node->id : null;
    }

    public function setDefaultEventEditor($editor)
    {
        Ide::get()->setUserConfigValue(CodeEditor::class . '.editorOnDoubleClick', $editor);
    }

    public function getDefaultEventEditor($request = true)
    {
        $editorType = Ide::get()->getUserConfigValue(CodeEditor::class . '.editorOnDoubleClick');

        if ($request && !$editorType) {
            $buttons = ['constructor' => 'Конструктор', 'php' => 'PHP редактор'];

            $dialog = new MessageBoxForm('Какой использовать редактор для редактирования событий?', $buttons);

            UXApplication::runLater(function () use ($dialog) {
                $dialog->toast('Используйте "Конструктор" если вы новичок!');
            });

            if ($dialog->showDialogWithFlag()) {
                $editorType = $dialog->getResult();

                if ($dialog->isChecked()) {
                    Ide::get()->setUserConfigValue(CodeEditor::class . '.editorOnDoubleClick', $editorType);
                }
            }
        }

        return $editorType;
    }

    function getMarkerNode()
    {
        return $this->markerNode;
    }
}