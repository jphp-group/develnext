<?php
namespace php\gui\framework;

use behaviour\SetTextBehaviour;
use php\format\ProcessorException;
use php\framework\Logger;
use php\game\UXSpriteView;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\behaviour\custom\BehaviourManager;
use php\gui\framework\behaviour\custom\FactoryBehaviourManager;
use php\gui\UXApplication;
use php\gui\UXData;
use php\gui\UXImageArea;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXNodeWrapper;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lib\arr;
use php\lib\str;
use php\xml\DomElement;
use php\xml\DomNode;
use php\xml\XmlProcessor;

class AbstractFactory
{
    const DEFAULT_PATH = 'res://.factories/';

    /**
     * xml
     * @var string[]
     */
    protected $prototypes = [];

    /**
     * @var DomElement[]
     */
    protected $prototypeElements = [];

    /**
     * @var DomElement[]
     */
    protected $prototypeData = [];

    /**
     * @var UXNode[]
     */
    protected $prototypeInstances = [];

    /**
     * @var DomElement[]
     */
    protected $prototypeImports = [];

    /**
     * @var EventBinder
     */
    protected $eventBinder;

    /**
     * @var BehaviourManager
     */
    protected $behaviourManager;

    /**
     * @var string
     */
    protected $factoryName = null;

    /**
     * @var UXLoader
     */
    protected $loader;

    /**
     * @param null|string $path
     */
    public function __construct($path = null)
    {
        $this->loadPrototypes($path);

        $this->loader = new UXLoader();
        $this->eventBinder = new EventBinder(null, $this);
        $this->behaviourManager = $behaviourManager = new FactoryBehaviourManager($this);

        try {
            $name = Str::replace(get_class($this), '\\', '/');

            BehaviourLoader::load("res://$name.behaviour", $behaviourManager);
        } catch (IOException $e) {
            ;
        }
    }

    /**
     * @param $name
     * @return Instances
     */
    public function __get($name)
    {
        $instances = $this->prototypeInstances[$name];

        return new Instances((array) $instances);
    }

    /**
     * @return Instances
     */
    public function getAllInstances()
    {
        return new Instances( arr::toList($this->prototypeInstances) );
    }

    protected function makeNode($id)
    {
        $element = $this->prototypeElements[$id];

        $node = null;
        $attrs = $element->getAttributes();

        switch ($element->getTagName()) {
            case 'SpriteView':
                $node = new UXSpriteView();
                $node->size = [$attrs['width'], $attrs['height']];
                break;
            case 'ImageViewEx':
                $node = new UXImageArea();
                $node->size = [$attrs['width'], $attrs['height']];
                $node->text = $attrs['text'];
                $node->textColor = $attrs['textFill'];
                $node->backgroundColor = $attrs['background'];

                foreach (['autoSize', 'proportional', 'stretch', 'centered', 'mosaic', 'mosaicGap'] as $prop) {
                    $node->{$prop} = $attrs[$prop] == 'true';
                }
                break;
        }

        if ($node != null) {
            $node->id = $attrs['id'];

            if (isset($attrs['opacity'])) {
                $node->opacity = $attrs['opacity'];
            }

            if (isset($attrs['rotate'])) {
                $node->rotate = $attrs['rotate'];
            }

            $node->focusTraversable = $attrs['focusTraversable'] == 'true';

            $node->position = [$attrs['layoutX'], $attrs['layoutY']];

            return $node;
        }

        return $this->loader->loadFromString($this->prototypes[$id]);
    }

    /**
     * @param string $id
     * @return UXNode
     * @throws \Exception
     * @throws IllegalArgumentException
     */
    public function create($id)
    {
        if ($prototype = $this->prototypes[$id]) {
            $data = new UXData();

            if ($_data = $this->prototypeData[$id]) {
                foreach ($_data->getAttributes() as $name => $value) {
                    $data->set($name, $value);
                }
            }

            $node = $this->makeNode($id);

            $node->data('-factory', $this);
            $node->data('-factory-name', $this->factoryName);
            $node->data('-factory-id', $factoryId = ($this->factoryName ? $this->factoryName . ".$id" : $id));

            UXNodeWrapper::get($node)->applyData($data);

            $this->eventBinder->loadBind($node, $id, __CLASS__, true);

            $this->behaviourManager->applyForInstance($id, $node);

            $this->prototypeInstances[$id][] = $node;

            return $node;
        }

        throw new IllegalArgumentException("Unable to find '$id' prototype in factory");
    }

    protected function getResourceName()
    {
        $class = get_class($this);

        if (app()->getNamespace()) {
            $class = Str::replace($class, app()->getNamespace(), '');

            if (Str::startsWith($class, '\\')) {
                $class = Str::sub($class, 1);
            }

            if (Str::startsWith($class, 'factories\\')) {
                $class = Str::sub($class, 10);
            }
        }

        return Str::replace($class, '\\', '/');
    }

    protected function loadPrototypes($path = null)
    {
        $path = $path ?: static::DEFAULT_PATH . $this->getResourceName() . '.factory';

        $this->prototypes = [];
        $this->prototypeData = [];
        $this->prototypeImports = [];
        $this->prototypeInstances = [];

        Stream::tryAccess($path, function (Stream $stream) {
            $xml = new XmlProcessor();

            try {
                $document = $xml->parse($stream);
                $this->prototypeImports = $document->findAll("/processing-instruction('import')");

                /** @var DomElement $element */
                foreach ($document->findAll("/AnchorPane//*") as $element) {
                    $id = $element->getAttribute('id');

                    if (!$id) continue;

                    switch ($element->getTagName()) {
                        case 'Data':
                            if (str::startsWith($id, 'data-')) {
                                $id = str::sub($id, 5);
                                $this->prototypeData[$id] = $element;
                            }
                            break;

                        default:
                            $this->prototypeElements[$id] = $element;
                            $this->prototypes[$id] = $this->makeXmlForLoader($element, $this->prototypeImports);
                            break;
                    }
                }

            } catch (ProcessorException $e) {
                throw new IOException("Unable to load {$stream->getPath()}, {$e->getMessage()}");
            } catch (IOException $e) {
                throw new IOException("Unable to load {$stream->getPath()}, {$e->getMessage()}");
            }
        });
    }

    protected function makeXmlForLoader(DomNode $node, $imports)
    {
        $processor = new XmlProcessor();
        $document = $processor->createDocument();

        /** @var DomElement $cloneNode */
        $cloneNode = $document->importNode($node, true);

        $document->appendChild($cloneNode);
        $element = $document->getDocumentElement();

        $element->setAttribute('xmlns', 'http://javafx.com/javafx/8');
        $element->setAttribute('xmlns:fx', 'http://javafx.com/fxml/1');

        foreach ($imports as $import) {
            $cloneImport = $import->cloneNode(true);
            $document->adoptNode($cloneImport);

            $document->insertBefore($cloneImport, $document->getDocumentElement());
        }

        $format = $processor->format($document);

        return $format;
    }
}