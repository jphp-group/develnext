<?php
namespace ide\formats;

use Exception;
use ide\editors\FormEditor;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use php\gui\designer\UXDesigner;
use php\gui\layout\UXPane;
use php\gui\UXDialog;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\io\Stream;
use php\xml\DomDocument;
use php\xml\XmlProcessor;
use ReflectionClass;

class GuiFormDumper extends AbstractFormDumper
{
    /**
     * @var XmlProcessor
     */
    protected $processor;

    /**
     * @var AbstractFormElementTag[]
     */
    protected $formElementTags;

    /**
     * @var UXDesigner
     */
    protected $designer;

    /**
     * @param array $formElementTags
     */
    function __construct(array $formElementTags)
    {
        $this->processor = new XmlProcessor();
        $this->formElementTags = $formElementTags;
    }

    public function load(FormEditor $editor)
    {
        $this->designer = $editor->getDesigner();

        $loader = new UXLoader();
        $layout = $loader->load($editor->getFile());

        if ($layout instanceof UXPane) {
            $editor->setLayout($layout);
        }
    }

    public function save(FormEditor $editor)
    {
        $this->designer = $editor->getDesigner();

        $document = $this->processor->createDocument();

        $element = $this->createElementTag($editor->getLayout(), $document, false);
        $element->setAttribute('xmlns', 'http://javafx.com/javafx/8');
        $element->setAttribute('xmlns:fx', 'http://javafx.com/fxml/1');

        $document->appendChild($element);

        $this->appendImports($this->designer->getNodes(), $document);

        Stream::tryAccess($editor->getFile(), function (Stream $output) use ($document) {
            $this->processor->formatTo($document, $output);
        }, 'w+');
    }

    /**
     * @param UXNode[] $nodes
     * @param DomDocument $document
     */
    public function appendImports(array $nodes, DomDocument $document)
    {
        $import = $document->createProcessingInstruction('import', 'javafx.scene.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.layout.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.control.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'java.lang.*');
        $document->insertBefore($import, $document->getDocumentElement());
    }

    public function createElementTag(UXNode $node, DomDocument $document, $ignoreUnregistered = true)
    {
        if ($ignoreUnregistered && $this->designer && !$this->designer->isRegisteredNode($node)) {
            return null;
        }

        $class = new ReflectionClass($node);

        do {
            $tag = $this->formElementTags[$class->getName()];

            if ($tag != null) {
                break;
            }

            $class = $class->getParentClass();

            if ($class == null) {
                break;
            }
        } while ($tag == null);

        if ($tag == null || $tag->isAbstract()) {
            return null;
        }

        $element = $document->createElement($tag->getTagName());

        $tag->writeAttributes($node, $element);
        $tag->writeContent($node, $element, $document, $this);

        while ($class != null) {
            $class = $class->getParentClass();

            if ($class != null) {
                $tag = $this->formElementTags[$class->getName()];

                if ($tag != null) {
                    $tag->writeAttributes($node, $element);
                    $tag->writeContent($node, $element, $document, $this);
                }
            }
        }

        return $element;
    }
}