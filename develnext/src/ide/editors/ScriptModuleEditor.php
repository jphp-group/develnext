<?php
namespace ide\editors;

use Files;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\form\FormElementTypePane;
use ide\editors\form\FormNamedBlock;
use ide\editors\menu\ContextMenu;
use ide\formats\form\AbstractFormDumper;
use ide\formats\GuiFormDumper;
use ide\formats\ScriptModuleFormat;
use ide\forms\MainForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\ProjectIndexer;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use ide\scripts\ScriptComponentManager;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractScript;
use php\gui\framework\DataUtils;
use php\gui\framework\ScriptManager;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCell;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\io\File;
use php\lib\Items;
use php\lib\Str;
use stdClass;

/**
 * Class ScriptModuleEditor
 * @package ide\editors
 *
 * @property ScriptModuleFormat $format
 */
class ScriptModuleEditor extends FormEditor
{
    /** @var ScriptComponentManager */
    protected $manager;

    /**
     * @var array
     */
    protected $properties;

    public function __construct($file)
    {
        $this->manager = new ScriptComponentManager();
        $this->properties = [];

        parent::__construct($file, new GuiFormDumper([]));

        $this->behaviourManager->setTargetGetter(function ($nodeId) {
            $container = $this->manager->findById($nodeId);

            if ($container) {
                return $container->getType();
            }

            return null;
        });
    }

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __get($name)
    {
        return $this->properties[$name];
    }

    protected function reindexImpl(ProjectIndexer $indexer)
    {
        $result = [];

        $indexer->remove($this->file, '_objects');

        $index = [];

        foreach ($this->manager->getComponents() as $component) {
            $index[$component->id] = [
                'id' => $component->id,
                'type' => get_class($component->getType()),
            ];
        }

        $indexer->set($this->file, '_objects', $index);

        return $result;
    }


    public function save()
    {
        foreach ($this->manager->getComponents() as $el) {
            $this->manager->saveContainer($el);
        }

        $this->saveOthers();

        /** @var ScriptComponentContainer[] $containers */
        $containers = Items::sort($this->manager->getComponents(), function (ScriptComponentContainer $a, ScriptComponentContainer $b) {
            $aScore = $a->getY() * 1000 + $a->getX();
            $bScore = $b->getY() * 1000 + $b->getY();

            if ($aScore == $bScore) {
                return 0;
            }

            return $aScore > $bScore ? -1 : 1;
        }, true);

        $json = [
            'properties' => $this->properties,
            'scripts' => []
        ];

        foreach ($containers as $container) {
            $path = FileUtils::relativePath($this->file, $container->getConfigPath());
            $json['scripts'][] = $path;
        }

        $indexFile = FileUtils::stripExtension($this->codeFile) . ".json";
        Json::toFile($indexFile, $json);
    }

    public function addContainer(ScriptComponentContainer $container)
    {
        $this->manager->add($container);

        /** @var FormNamedBlock $node */
        $node = $container->getType()->createElement();
        $node->setTitle($container->id);

        $container->setIdeNode($node);
        $node->userData = $container;

        $node->position = [$container->getX(), $container->getY()];

        $node->watch('layoutX', function () use ($container, $node) {
            $container->setX($node->x);
        });
        $node->watch('layoutY', function () use ($container, $node) {
            $container->setY($node->y);
        });

        $this->layout->add($node);
        $this->reindex();
    }

    public function load()
    {
        $this->loadOthers();

        $indexFile = FileUtils::stripExtension($this->codeFile) . ".json";

        if (Files::exists($indexFile)) {
            $json = Json::fromFile($indexFile);
            $this->properties = (array) $json['properties'];
        }

        $this->layout = new UXAnchorPane();
        $this->layout->padding = 3;
        $this->layout->minSize = [800, 600];
        $this->layout->size = [800, 600];
        $this->layout->css('background-color', 'white');

        $files = File::of($this->file)->findFiles();

        foreach ($files as $file) {
            if (Str::endsWith($file, '.json')) {
                $container = $this->manager->loadContainer($file);

                if ($container) {
                    $this->addContainer($container);
                }
            }
        }
    }

    public function changeNodeId($container, $newId)
    {
        /** @var ScriptComponentContainer $container */
        if (!$this->checkNodeId($newId)) {
            return 'invalid';
        }

        if ($container && $container->id == $newId) {
            return '';
        }

        foreach ($this->manager->getComponents() as $el) {
            if ($el->id == $newId) {
                return 'busy';
            }
        }

        $oldId = $container->id;

        if ($this->manager->renameId($container, $newId)) {
            $this->behaviourManager->changeTargetId($oldId, $newId);
            $binds = $this->eventManager->renameBind($oldId, $newId);

            $container->getIdeNode()->setTitle($newId);

            foreach ($binds as $bind) {
                $this->actionEditor->renameMethod($bind['className'], $bind['methodName'], $bind['newMethodName']);
            }

            $this->codeEditor->load();
            $this->reindex();
        } else {
            return 'invalid';
        }

        return '';
    }

    public function getNodeId($node)
    {
        /** @var ScriptComponentContainer $container */
        $container = $node->userData;

        if ($container instanceof ScriptComponentContainer) {
            return $container->id;
        }

        return null;
    }

    protected function makeDesigner($fullArea = true)
    {
        return parent::makeDesigner(true);
    }

    public function deleteNode($node)
    {
        /** @var ScriptComponentContainer $container */
        $container = $node->userData;

        if (!($container instanceof ScriptComponentContainer)) {
            return;
        }

        $this->manager->remove($container);

        $designer = $this->designer;

        $designer->unselectNode($node);
        $designer->unregisterNode($node);

        $node->parent->remove($node);

        if ($container && $container->id) {
            $binds = $this->eventManager->findBinds($container->id);

            foreach ($binds as $bind) {
                $this->actionEditor->removeMethod($bind['className'], $bind['methodName']);
            }
        }

        if ($container && $container->id && $this->eventManager->removeBinds($container->id)) {
            $this->codeEditor->load();
        }

        if ($container && $container->id) {
            $this->behaviourManager->removeBehaviours($container->id);
            $this->behaviourManager->save();
        }

        File::of($container->getConfigPath())->delete();
        $this->reindex();
    }

    public function getModules()
    {
        return [];
    }

    /**
     * @return ScriptComponentManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    public function getModuleName()
    {
        return FileUtils::stripExtension(File::of($this->file)->getName());
    }

    protected $forms = [];

    /**
     * @return FormEditor[]
     * @throws \Exception
     */
    public function getFormEditors()
    {
        $project = Ide::get()->getOpenedProject();

        if (!$project) {
            return [];
        }

        /** @var GuiFrameworkProjectBehaviour $gui */
        $gui = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

        $forms = $gui->getFormEditorsOfModule($this->getModuleName());

        return $forms;
    }

    protected function _onAreaMouseUp(UXMouseEvent $e)
    {
        $selected = $this->elementTypePane->getSelected();

        $this->save();

        /** @var AbstractScriptComponent $selected */
        if ($selected) {
            $node = $selected->createElement();

            $container = new ScriptComponentContainer($selected, $this->makeId($selected->getIdPattern()));
            $container->setIdeNode($node);

            $container->setConfigPath("{$this->file}/{$container->id}.json");
            $node->setTitle($container->id);

            $node->userData = $container;

            $this->manager->add($container);

            $size = $node->size;

            $node->watch('layoutX', function () use ($container, $node) {
                $container->setX($node->x);
            });
            $node->watch('layoutY', function () use ($container, $node) {
                $container->setY($node->y);
            });

            $position = [$e->x, $e->y];

            $snapSize = $this->designer->snapSize;

            if ($this->designer->snapEnabled) {
                $size[0] = floor($size[0] / $snapSize) * $snapSize;
                $size[1] = floor($size[1] / $snapSize) * $snapSize;

                $position[0] = floor($position[0] / $snapSize) * $snapSize;
                $position[1] = floor($position[1] / $snapSize) * $snapSize;
            }

            $node->position = $position;

            $this->layout->add($node);
            $this->designer->registerNode($node);

            if (!$e->controlDown) {
                $this->elementTypePane->clearSelected();
            }

            foreach ($selected->getInitProperties() as $key => $property) {
                $container->{$key} = $property['value'];
            }

            $this->manager->add($container);
            $this->designer->requestFocus();

            $this->reindex();
            $this->save();
        } else {
            $this->updateProperties($this);
        }
    }

    public function makeId($idPattern)
    {
        $id = Str::format($idPattern, '');

        if (Files::exists($this->file . "/" . $id . '.json')) {
            $id = Str::format($idPattern, 'Alt');

            if (Files::exists($this->file . "/$id.json")) {
                $n = 3;

                do {
                    $id = Str::format($idPattern, $n++);
                } while (Files::exists($this->file . "/$id.json"));
            }
        }

        return $id;
    }
}