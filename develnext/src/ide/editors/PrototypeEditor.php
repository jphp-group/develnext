<?php
namespace ide\editors;

use game\GameEntity;
use game\SpriteSpec;
use ide\action\ActionEditor;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\form\IdeBehaviourPane;
use ide\editors\form\IdeEventListPane;
use ide\editors\form\IdePropertiesPane;
use ide\editors\form\IdeSpritePane;
use ide\editors\form\IdeTabPane;
use ide\formats\form\elements\GameObjectFormElement;
use ide\formats\form\SourceEventManager;
use ide\formats\GuiFormDumper;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\designer\UXDesignProperties;
use php\gui\UXApplication;
use php\io\IOException;
use php\io\Stream;
use php\util\Configuration;
use stdClass;

class PrototypeEditor extends CodeEditor
{
    /**
     * @var SourceEventManager
     */
    protected $eventManager;

    /**
     * @var IdeBehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var IdeBehaviourPane
     */
    protected $behaviourPane;

    /**
     * @var IdeEventListPane
     */
    protected $eventListPane;

    /**
     * @var IdeTabPane
     */
    protected $editorPane;

    /**
     * @var GameObjectFormElement
     */
    protected $element;

    /**
     * @var bool
     */
    protected $opened = false;

    /**
     * @var ActionEditor
     */
    protected $actionEditor;

    /**
     * @var Configuration
     */
    protected $defaultsConfig;

    /**
     * @var IdeSpritePane
     */
    protected $spritePane;

    /**
     * @var stdClass
     */
    protected $target;

    /**
     * GameSceneEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct($file, 'php', []);

        $this->target = new stdClass();

        $project = Ide::get()->getOpenedProject();

        if ($project && $project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
            /** @var GuiFrameworkProjectBehaviour $behaviour */
            $behaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

            $this->spritePane = new IdeSpritePane($behaviour->getSpriteManager());
            $this->spritePane->on('change', function (SpriteSpec $spec = null) {
                $this->target->sprite = $spec ? $spec->name : null;
                $this->save();
            });
        }

        $this->element          = new GameObjectFormElement();
        $this->eventManager     = new SourceEventManager($this->file);
        $this->behaviourManager = new IdeBehaviourManager(FileUtils::stripExtension($file) . '.behaviour');
        $this->defaultsConfig   = new Configuration();
        $this->actionEditor     = new ActionEditor($file . '.axml');

        $this->registerDefaultCommands();
    }

    public function getIcon()
    {
        return $this->format->getIcon();
    }

    public function getTitle()
    {
        return $this->format->getTitle($this->file);
    }

    public function makeUi()
    {
        $textArea = parent::makeUi();

        return $textArea;
    }

    public function makeLeftPaneUi()
    {
        $ui = new IdeTabPane();

        $properties = new UXDesignProperties();
        $this->element->createProperties($properties);

        $pane = new IdePropertiesPane();
        $ui->addPropertiesPane($pane);

        $pane->setProperties($properties);
        $pane->update($this->target);
        $pane->addCustomNode($this->spritePane->makeUi());

        $pane = new IdeEventListPane($this->eventManager);
        $pane->setContext(GameEntity::class);
        $pane->setEventTypes($this->element->getEventTypes());
        $pane->setContextEditor($this);
        $pane->setCodeEditor($this);
        $pane->setActionEditor($this->actionEditor);

        $ui->addEventListPane($pane);

        $ui->addBehaviourPane(new IdeBehaviourPane($this->behaviourManager));

        $this->editorPane = $ui;
        return $ui;
    }

    /**
     * @return SourceEventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    public function open()
    {
        parent::open();

        $this->opened = true;

        $this->eventManager->load();

        $this->updateEventList();
        $this->updateProperties();

        UXApplication::runLater(function () {
            $this->textArea->requestFocus();
        });
    }

    public function close()
    {
        parent::close();

        $this->opened = false;

        if (FileSystem::getOpened() === $this) {
            $this->updateProperties();
        }
    }

    public function load()
    {
        parent::load();

        try {
            $this->defaultsConfig->load(FileUtils::stripExtension($this->file) . '.conf');

            foreach ($this->defaultsConfig->toArray() as $name => $value) {
                $this->target->{$name} = $value;
            }
        } catch (IOException $e) {
            ;
        }

        $this->spritePane->setSprite($this->target->sprite);

        $this->actionEditor->load();
        $this->behaviourManager->load();
    }

    public function save()
    {
        parent::save();

        $this->actionEditor->save();
        $this->behaviourManager->save();

        try {
            $this->defaultsConfig->clear();

            foreach ($this->target as $name => $value) {
                $this->defaultsConfig->set($name, $value);
            }

            $this->defaultsConfig->save(FileUtils::stripExtension($this->file) . '.conf');
        } catch (IOException $e) {
            ;
        }
    }

    private function updateProperties()
    {
    }

    private function updateEventList()
    {
        $this->eventManager->load();

        $this->editorPane->updateEventList('');
    }
}