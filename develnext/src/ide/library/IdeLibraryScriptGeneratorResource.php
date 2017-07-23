<?php
namespace ide\library;


use ide\utils\FileUtils;
use php\format\ProcessorException;
use php\io\IOException;
use php\lib\arr;
use php\lib\str;
use php\util\Flow;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\DomNode;
use php\xml\XmlProcessor;

class IdeLibraryScriptGeneratorResource extends IdeLibraryResource
{
    /**
     * @var DomDocument
     */
    protected $document;

    /**
     * IdeLibraryScriptGeneratorResource constructor.
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->path = $path;

        $xml = new XmlProcessor();

        if ($path) {
            try {
                $this->document = $xml->parse(FileUtils::get($path . ".xml"));
                $this->valid = true;
            } catch (ProcessorException $e) {
                $this->valid = false;
                $this->document = $xml->createDocument();
            }
        } else {
            $this->document = $xml->createDocument();
            $this->valid = false;
        }
    }

    public function getName()
    {
        return $this->document->get('/scriptGenerator/name');
    }

    public function getDescription()
    {
        return $this->document->get('/scriptGenerator/description');
    }

    public function getAuthor()
    {
        return $this->document->get('/scriptGenerator/author');
    }

    public function getAuthorSite()
    {
        return $this->document->get('/scriptGenerator/authorSite');
    }

    public function getVersion()
    {
        return $this->document->get('/scriptGenerator/version') ?: '1.0';
    }

    public function getSource($param = '')
    {
        $sources = $this->document->findAll('/scriptGenerator/source');

        /** @var DomElement $one */
        foreach ($sources as $one) {
            if ($one->getAttribute('param') == $param || $one->getAttribute('param') == '~') {
                return $one->getTextContent();
            }
        }

        return null;
    }

    public function getSourceSyntax()
    {
        return $this->document->get('/scriptGenerator/source/@syntax');
    }

    public function getContexts()
    {
        $value = $this->document->get('/scriptGenerator/context');

        return Flow::of(str::split($value, ','))->map([str::class, 'trim'])->toArray();
    }

    public function hasContext($context)
    {
        foreach ($this->getContexts() as $one) {
            if (str::startsWith($context, $one)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'scriptGenerators';
    }

    function getIcon()
    {
        return 'icons/scriptHelper16.png';
    }


}