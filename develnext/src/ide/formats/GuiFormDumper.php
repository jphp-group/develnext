<?php
namespace ide\formats;

use Exception;
use ide\editors\FormEditor;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use ide\formats\form\tags\CloneFormElementTag;
use ide\Ide;
use ide\Logger;
use php\gui\designer\UXDesigner;
use php\gui\framework\DataUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\UXCustomNode;
use php\gui\UXData;
use php\gui\UXDialog;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\io\IOException;
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
        /** @var UXAnchorPane $layout */
        try {
            Stream::tryAccess($editor->getFile(), function ($stream) use ($loader, $editor) {
                $layout = $loader->load($stream);

                if ($layout instanceof UXPane) {
                    $editor->setLayout($layout);
                }
            });
        } catch (IOException $e) {
            Ide::get()->getMainForm()->toast('Ошибка загрузки формы: ' . $e->getMessage());
            $layout1 = new UXAnchorPane();
            $layout1->maxWidth = 99999999;
            $layout1->maxHeight = 99999999;

            $layout1->size = [500, 500];

            $editor->setLayout($layout1);
        }
    }

    public function save(FormEditor $editor)
    {
        $this->designer = $editor->getDesigner();

        DataUtils::cleanup($editor->getLayout());

        $document = $this->processor->createDocument();

        $element = $this->createElementTag($editor->getLayout(), $document, false);

        if ($element == null) {
            Logger::error("Unable to save editor '{$editor->getTitle()}'");
            return;
        }

        $element->setAttribute('xmlns', 'http://javafx.com/javafx/8');
        $element->setAttribute('xmlns:fx', 'http://javafx.com/fxml/1');

        $document->appendChild($element);

        $this->appendImports($this->designer->getNodes(), $document);

        $stream = null;

        try {
            $stream = Stream::of($editor->getFile(), 'w');
            $this->processor->formatTo($document, $stream);
        } catch (IOException $e) {
            Logger::warn("Unable to save file: {$e->getFile()}");
        } finally {
            if ($stream) $stream->close();
        }
    }

    /**
     * @param UXNode[] $nodes
     * @param DomDocument $document
     */
    public function appendImports(array $nodes, DomDocument $document)
    {
        $import = $document->createProcessingInstruction('import', 'javafx.scene.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.collections.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.layout.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.control.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.text.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.image.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.shape.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.web.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'java.lang.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'org.develnext.jphp.ext.javafx.classes.data.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'org.develnext.jphp.ext.javafx.support.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'org.develnext.jphp.ext.javafx.support.control.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'org.develnext.jphp.ext.game.support.*');
        $document->insertBefore($import, $document->getDocumentElement());
    }

    public function createElementTag(UXNode $node, DomDocument $document, $ignoreUnregistered = true)
    {
        if ($ignoreUnregistered && $this->designer
            && (!$this->designer->isRegisteredNode($node) && !($node instanceof UXData) && !($node instanceof UXCustomNode))) {
            return null;
        }

        $factoryId = $node->data('-factory-id');

        if ($factoryId || $node instanceof UXCustomNode) {
            $oTag = new CloneFormElementTag();
            $element = $document->createElement($oTag->getTagName());
        } else {
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

            $oTag = $tag;

            if (!$tag->isFinal()) {
                while ($class != null) {
                    $class = $class->getParentClass();

                    if ($class != null) {
                        $tag = $this->formElementTags[$class->getName()];

                        if ($tag != null) {
                            $tag->writeAttributes($node, $element);
                            $tag->writeContent($node, $element, $document, $this);

                            if ($tag->isFinal()) {
                                break;
                            }
                        }
                    }
                }
            }
        }

        $oTag->writeAttributes($node, $element);
        $oTag->writeContent($node, $element, $document, $this);

        return $element;
    }
}