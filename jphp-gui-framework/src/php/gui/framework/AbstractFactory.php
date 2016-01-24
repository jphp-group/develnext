<?php
namespace php\gui\framework;

use behaviour\SetTextBehaviour;
use php\gui\framework\behaviour\custom\BehaviourLoader;
use php\gui\framework\behaviour\custom\BehaviourManager;
use php\gui\framework\behaviour\custom\FactoryBehaviourManager;
use php\gui\UXApplication;
use php\gui\UXData;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXNodeWrapper;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lib\str;
use php\xml\DomElement;
use php\xml\DomNode;
use php\xml\XmlProcessor;

abstract class AbstractFactory
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
     * @throws Exception
     */
    public function __construct()
    {
        $name = Str::replace(get_class($this), '\\', '/');

        $this->loadPrototypes();

        $this->eventBinder = new EventBinder(null, $this);
        $this->behaviourManager = $behaviourManager = new FactoryBehaviourManager($this);
        BehaviourLoader::load("res://$name.behaviour", $behaviourManager);
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
     * @param string $id
     * @return UXNode
     * @throws Exception
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

            $loader = new UXLoader();
            $node = $loader->loadFromString($prototype);
            $node->data('-factory', $this);
            $node->data('-factory-name', $this->factoryName);
            $node->data('-factory-id', $this->factoryName ? $this->factoryName . ".$id" : $id);

            UXNodeWrapper::get($node)->applyData($data);

            $this->eventBinder->loadBind($node, $id, __CLASS__, true);

            $this->behaviourManager->applyForInstance($id, $node);

            $this->eventBinder->trigger($node, $id, 'create');

            $node->observer('parent')->addListener(function ($old, $new) use ($node, $id) {
                if (!$new) {
                    $this->eventBinder->trigger($node, $id, 'destroy');
                }
            });

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

        Stream::tryAccess($path, function (Stream $stream) {
            $xml = new XmlProcessor();

            try {
                $document = $xml->parse($stream);
                $this->prototypeImports = $document->findAll("/processing-instruction('import')");

                /** @var DomElement $element */
                foreach ($document->findAll("/AnchorPane//*") as $element) {
                    if (!$element->getAttribute('id')) continue;

                    switch ($element->getTagName()) {
                        case 'Data':
                            $id = $element->getAttribute('id');

                            if (str::startsWith($id, 'data-')) {
                                $id = str::sub($id, 5);
                                $this->prototypeData[$id] = $element;
                            }
                            break;

                        default:
                            $this->prototypes[$element->getAttribute('id')] = $this->makeXmlForLoader($element, $this->prototypeImports);
                            break;
                    }
                }

            } catch (IOException $e) {
                throw new IOException("Unable to load {$stream->getPath()}, {$e->getMessage()}");
            }
        });
    }

    protected function makeXmlForLoader(DomNode $node, $imports)
    {
        $processor = new XmlProcessor();
        $document = $processor->createDocument();

        $cloneNode = $document->importNode($node, true);

        $document->appendChild($cloneNode);

        foreach ($imports as $import) {
            $cloneImport = $import->cloneNode(true);
            $document->adoptNode($cloneImport);

            $document->insertBefore($cloneImport, $document->getDocumentElement());
        }

        $format = $processor->format($document);

        return $format;
    }
}