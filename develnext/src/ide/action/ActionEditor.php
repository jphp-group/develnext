<?php
namespace ide\action;

use ide\editors\FormEditor;
use ide\utils\FileUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\UXCell;
use php\gui\UXListView;
use php\gui\UXLoader;
use ide\editors\AbstractEditor;
use php\gui\UXNode;
use php\io\File;
use php\io\FileStream;
use php\lib\Items;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class ActionConstructorPane
 * @package ide\editors\action
 */
class ActionEditor extends AbstractEditor
{
    /**
     * @var UXAnchorPane
     */
    protected $pane;

    /**
     * @var ActionScript
     */
    protected $container;

    /**
     * @var DomDocument
     */
    protected $document;

    /**
     * @var ActionManager
     */
    protected $manager;

    /**
     * @var DomDocument[]
     */
    protected $snapshots = [];

    /**
     * @var FormEditor
     */
    protected $formEditor;

    /**
     * ActionEditor constructor.
     * @param string $file
     * @param ActionManager $manager
     */
    public function __construct($file, ActionManager $manager = null)
    {
        parent::__construct($file);

        $this->manager = $manager == null ? ActionManager::get() : $manager;
    }

    public function makeSnapshot()
    {
        $this->snapshots[] = $this->document;

        $this->load();
    }

    public function restoreSnapshot()
    {
        if ($this->snapshots) {
            $this->document = Items::pop($this->snapshots);
        }
    }

    public function clearSnapshots()
    {
        $this->snapshots = [];
    }

    /**
     * @return ActionManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return UXAnchorPane
     */
    public function getPane()
    {
        return $this->pane;
    }

    protected function recoverDocument()
    {
        if (!$this->document->find('/root')) {
            $root = $this->document->createElement('root');
            $this->document->appendChild($root);
        }
    }

    /**
     * @return FormEditor
     */
    public function getFormEditor()
    {
        return $this->formEditor;
    }

    /**
     * @param FormEditor $formEditor
     */
    public function setFormEditor($formEditor)
    {
        $this->formEditor = $formEditor;
    }

    public function load()
    {
        $xml = new XmlProcessor();

        try {
            if (File::of($this->file)->exists()) {
                $this->document = $xml->parse(FileUtils::get($this->file));
            } else {
                $this->document = $xml->createDocument();
            }
        } catch (\Exception $e) {
            echo "-> $this->file\n";
            $this->document = $xml->createDocument();
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }

        $this->recoverDocument();

        $this->container = new ActionScript($this->document);
    }

    /**
     * @param $class
     * @param $method
     * @return Action[]
     */
    public function findMethod($class, $method)
    {
        if (!$this->document) {
            return [];
        }

        $domActions = $this->document->findAll("/root/class[@name='$class']/method[@name='$method']/*");

        $actions = [];

        foreach ($domActions as $domAction) {
            $action = $this->manager->buildAction($domAction);

            if ($action) {
                $actions[] = $action;
            }
        }

        $this->calculateLevels($actions);

        return $actions;
    }

    public function save()
    {
        if ($this->document) {
            $xml = new XmlProcessor();
            FileUtils::put($this->file, $xml->format($this->document));
        }
    }

    /**
     * @deprecated
     * @return UXNode
     */
    public function makeUi()
    {
        $this->pane = (new UXLoader())->load('res://.forms/blocks/_ActionConstructor.fxml');

        /** @var UXListView $list */
        $list = $this->pane->lookup('#list');

        $list->setCellFactory([$this, 'listCellFactory']);

        return $this->pane;
    }

    protected function fetchMethodDom($class, $method)
    {
        $domMethod = $this->document->find("/root/class[@name='$class']/method[@name='$method']");

        if (!$domMethod) {
            $domClass = $this->document->find("/root/class[@name='$class']");

            if (!$domClass) {
                $domClass = $this->document->createElement('class', ['@name' => $class]);
                $this->document->getDocumentElement()->appendChild($domClass);
            }

            $domMethod = $this->document->createElement('method', ['@name' => $method]);

            $domClass->appendChild($domMethod);
        }

        return $domMethod;
    }

    public function removeMethod($class, $method)
    {
        $domMethod = $this->fetchMethodDom($class, $method);

        $domClass = $this->document->find("/root/class[@name='$class']");

        if ($domClass) {
            $domClass->removeChild($domMethod);
        }

        $this->save();
    }

    /**
     * @param Action[] $actions
     */
    public function calculateLevels(array $actions)
    {
        ActionScript::calculateLevels($actions);
    }

    /**
     * @param $class
     * @param $method
     * @param Action[] $actions
     */
    public function updateMethod($class, $method, array $actions)
    {
        $this->removeMethod($class, $method);

        foreach ($actions as $action) {
            $this->addAction($action, $class, $method);
        }

        $this->calculateLevels($actions);

        $this->save();
    }

    public function addAction(Action $action, $class, $method)
    {
        $element = $this->document->createElement($action->getType()->getTagName());

        $action->getType()->serialize($action, $element, $this->document);

        $methodDom = $this->fetchMethodDom($class, $method);
        $methodDom->appendChild($element);

        $this->save();
    }

    public function removeActions($class, $method, ...$indexes)
    {
        $domMethod = $this->fetchMethodDom($class, $method);

        $domActions = [];
        foreach ($indexes as $index) {
            $index++;

            $domAction = $domMethod->find("(./*)[$index]");

            if ($domAction) {
                $domActions[] = $domAction;
            }
        }

        foreach ($domActions as $domAction) {
            $domMethod->removeChild($domAction);
        }

        $this->save();
    }

    public function renameMethod($className, $methodName, $newMethodName)
    {
        /** @var DomElement $domMethod */
        $domMethod = $this->document->find("/root/class[@name='$className']/method[@name='$methodName']");

        if ($domMethod) {
            $domMethod->setAttribute('name', $newMethodName);

            $this->save();
        }
    }
}