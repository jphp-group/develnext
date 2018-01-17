<?php
namespace ide\webplatform\formats;

use ide\editors\FormEditor;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElementTag;
use ide\misc\EventHandlerBehaviour;
use php\format\JsonProcessor;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\UXButton;
use php\gui\UXNode;
use php\io\IOException;
use php\xml\DomDocument;
use php\xml\DomElement;

/**
 * Class WebFormDumper
 * @package ide\webplatform\formats
 */
class WebFormDumper extends AbstractFormDumper
{
    use EventHandlerBehaviour;

    /**
     * @var JsonProcessor
     */
    protected $json;

    /**
     * @var array
     */
    private $formElementTags;

    /**
     * WebFormDumper constructor.
     */
    public function __construct(array $formElementTags)
    {
        $this->json = new JsonProcessor(JsonProcessor::SERIALIZE_PRETTY_PRINT);
        $this->formElementTags = $formElementTags;
    }

    /**
     * @param AbstractFormElementTag[] $formElementTags
     */
    public function setFormElementTags($formElementTags)
    {
        $this->formElementTags = $formElementTags;
    }


    public function load(FormEditor $editor)
    {
        $designer = $editor->getDesigner();

        /** @var UXAnchorPane $layout */
        try {
            $layout = new UXAnchorPane();
            $layout->size = [640, 480];
            $layout->backgroundColor = 'yellow';
            $layout->style = '-fx-border-width: 1; -fx-border-color: red;';

            $layout->add(new UXButton('teeeeeeeeeeees'));

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

        $layout = $editor->getLayout();
    }

    /**
     * @param UXNode[] $nodes
     * @param DomDocument $document
     */
    public function appendImports(array $nodes, DomDocument $document)
    {
    }

    /**
     * @param FormEditor $editor
     * @param UXNode $node
     * @param DomDocument $document
     *
     * @param bool $ignoreUnregistered
     * @return DomElement
     */
    public function createElementTag(FormEditor $editor = null, UXNode $node, DomDocument $document, $ignoreUnregistered = true)
    {
    }
}