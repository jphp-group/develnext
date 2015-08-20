<?php
namespace ide\editors\action;
use php\gui\layout\UXAnchorPane;
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
     * @var ActionContainer
     */
    protected $container;

    /**
     * @var DomDocument
     */
    protected $document;

    /**
     * @return UXAnchorPane
     */
    public function getPane()
    {
        return $this->pane;
    }

    public function load()
    {
        $xml = new XmlProcessor();

        try {
            if (File::of($this->file)->exists()) {
                $this->document = $xml->parse($this->file);
            } else {
                $this->document = $xml->createDocument();
            }
        } catch (\Exception $e) {
            $this->document = $xml->createDocument();
        }

        $this->container = new ActionContainer($this->document);
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
     * @return UXNode
     */
    public function makeUi()
    {
        return $this->pane = (new UXLoader())->load('res://.forms/blocks/_ActionConstructor.fxml');
    }
}