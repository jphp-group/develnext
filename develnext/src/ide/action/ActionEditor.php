<?php
namespace ide\action;

use ide\utils\FileUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\UXCell;
use php\gui\UXListView;
use php\gui\UXLoader;
use ide\editors\AbstractEditor;
use php\gui\UXNode;
use php\io\File;
use php\io\FileStream;
use php\xml\DomDocument;
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
     * ActionEditor constructor.
     * @param string $file
     * @param ActionManager $manager
     */
    public function __construct($file, ActionManager $manager = null)
    {
        parent::__construct($file);

        $this->manager = $manager == null ? ActionManager::get() : $manager;
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
            $actions[] = $this->manager->buildAction($domAction);
        }

        return $actions;
    }

    public function save()
    {
        $xml = new XmlProcessor();
        $stream = new FileStream($this->file, 'w+');

        try {
            $xml->formatTo($this->document, $stream);
        } finally {
            $stream->close();
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
}