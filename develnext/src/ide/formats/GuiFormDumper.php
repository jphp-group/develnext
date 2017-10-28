<?php
namespace ide\formats;

use Exception;
use ide\editors\FormEditor;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use ide\formats\form\tags\CloneFormElementTag;
use ide\Ide;
use ide\Logger;
use ide\misc\EventHandlerBehaviour;
use ide\utils\FileUtils;
use php\format\ProcessorException;
use php\gui\designer\UXDesigner;
use php\gui\framework\DataUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\UXCustomNode;
use php\gui\UXData;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXScene;
use php\io\IOException;
use php\io\MemoryStream;
use php\io\Stream;
use php\lib\fs;
use php\lib\reflect;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;
use ReflectionClass;

class GuiFormDumper extends AbstractFormDumper
{
    use EventHandlerBehaviour;

    /**
     * @var XmlProcessor
     */
    protected $processor;

    /**
     * @var AbstractFormElementTag[]
     */
    protected $formElementTags;

    /**
     * @var AbstractFormElementTag[]
     */
    protected $formElementTagsByTag;

    /**
     * @var XmlProcessor
     */
    protected $xml;

    /**
     * @var UXForm
     */
    protected $testScene;

    /**
     * @param AbstractFormElementTag[] $formElementTags
     */
    function __construct(array $formElementTags)
    {
        $this->testScene = new UXForm();
        $this->processor = new XmlProcessor();
        $this->formElementTags = $formElementTags;

        foreach ($formElementTags as $tag) {
            $this->formElementTagsByTag[$tag->getTagName()] = $tag;
        }

        $this->xml = new XmlProcessor();
    }

    /**
     * @param form\AbstractFormElementTag[] $formElementTags
     */
    public function setFormElementTags($formElementTags)
    {
        $this->formElementTags = $formElementTags;
    }

    public function fetchFormFile(FormEditor $editor)
    {
        $xml = $this->xml;

        try {
            $document = $xml->parse(FileUtils::get($editor->getFxmlFile()));

            /** @var DomElement $element */
            foreach ($document->findAll('//*[@id]') as $element) {
                $tagName = $element->getTagName();

                if ($tagElement = $this->formElementTagsByTag[$tagName]) {
                    if ($element->getAttribute('id')) {
                        $tagElement->readContent($document, $element);
                    }
                }
            }

            $stream = new MemoryStream();

            $xml->formatTo($document, $stream);
            $stream->seek(0);

            return $stream;
        } catch (IOException $e) {
            Logger::error("Unable fetchFormFile(), {$e->getMessage()}");
            return null;
        } catch (ProcessorException $e) {
            Logger::error("Unable parse xml file {$editor->getFxmlFile()}, {$e->getMessage()}");
            return null;
        }
    }

    public function load(FormEditor $editor)
    {
        $designer = $editor->getDesigner();

        $loader = new UXLoader();
        /** @var UXAnchorPane $layout */
        try {
            $stream = $this->fetchFormFile($editor);

            if ($stream == null) {
                throw new IOException();
            }

            $layout = $loader->load($stream);

            if (!$layout->backgroundColor) {
                $layout->backgroundColor = '#f2f2f2';
            }

            if ($layout instanceof UXPane) {
                $editor->setLayout($layout);

                $this->trigger('load', [$editor, $layout]);
            } else {
                throw new IOException();
            }

            return true;
        } catch (IOException $e) {
            $editor->setIncorrectFormat(true);
            $editor->setLayout(new UXAnchorPane());
            return false;
        }
    }

    public function save(FormEditor $editor)
    {
        $this->trigger('save', [$editor]);

        $designer = $editor->getDesigner();

        DataUtils::cleanup($editor->getLayout());

        $document = $this->processor->createDocument();

        $this->testScene->clearStylesheets();
        $this->testScene->children->clear();

        foreach ($editor->getStylesheets() as $stylesheet) {
            $this->testScene->addStylesheet($stylesheet);
        }

        $element = $this->createElementTag($editor, $editor->getLayout(), $document, false, $testScene);

        if ($element == null) {
            Logger::warn("Unable to save editor '{$editor->getTitle()}'");
            return;
        }

        $element->setAttribute('xmlns', 'http://javafx.com/javafx/8');
        $element->setAttribute('xmlns:fx', 'http://javafx.com/fxml/1');

        $document->appendChild($element);

        $this->appendImports($designer->getNodes(), $document);

        $stream = null;

        try {
            fs::ensureParent($editor->getFxmlFile());
            $stream = Stream::of($editor->getFxmlFile(), 'w');
            $this->processor->formatTo($document, $stream);
        } catch (IOException $e) {
            Logger::warn("Unable to save file: {$editor->getFxmlFile()}");
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

        $import = $document->createProcessingInstruction('import', 'javafx.scene.paint.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.scene.web.*');
        $document->insertBefore($import, $document->getDocumentElement());

        $import = $document->createProcessingInstruction('import', 'javafx.geometry.*');
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

        $this->trigger('appendImports', [$nodes, $document]);
    }

    public function createElementTag(FormEditor $editor = null, UXNode $node, DomDocument $document, $ignoreUnregistered = true)
    {
        $designer = $editor ? $editor->getDesigner() : null;

        if ($ignoreUnregistered && $designer
            && (!$designer->isRegisteredNode($node) && !($node instanceof UXData) && !($node instanceof UXCustomNode))) {
            return null;
        }

        if ($node->classes->has('x-system-designer-element') || $node->classes->has('x-system-element')) {
            return null;
        }

        /*if ($ignoreUnregistered && !$designer) {
            if ($node instanceof UXData || $node instanceof UXCustomNode) {
                return null;
            }
        }*/

        if (!$this->formElementTags) {
            Logger::warn("createElementTag() doesn't work properly, tag list is empty");
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

            //Logger::debug("Write " . reflect::typeOf($tag) . ", id = $node->id, node is " . reflect::typeOf($node));
            $element = $document->createElement($tag->getTagName());

            $oTag = $tag;

            if (!$tag->isFinal()) {
                while ($class != null) {
                    /** @var ReflectionClass $class */
                    $class = $class->getParentClass();

                    if ($class != null) {
                        $tag = $this->formElementTags[$class->getName()];

                        if ($tag != null) {
                            //Logger::debug("Write " . reflect::typeOf($tag) . ", id = $node->id");
                            $testNode = $tag->createTestNode($node, $this->testScene);

                            $tag->writeAttributes($node, $element);
                            $tag->writeContent($node, $element, $document, $this);

                            if ($testNode) {
                                $testNode->free();
                            }

                            if ($tag->isFinal()) {
                                break;
                            }
                        } else {
                            if (!$class->isAbstract()) {
                                //Logger::warn("Skip {$class->getName()}, element is not found.");
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