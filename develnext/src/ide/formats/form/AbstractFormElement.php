<?php
namespace ide\formats\form;

use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\Logger;
use php\gui\designer\UXDesignProperties;
use php\gui\UXImage;
use php\gui\UXNode;
use php\io\IOException;
use php\io\Stream;
use php\lib\Items;
use php\lib\String;
use php\util\Flow;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;

/**
 * Class AbstractFormElement
 * @package ide\formats\form
 */
abstract class AbstractFormElement
{
    /**
     * @var FormElementConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $initProperties = [];

    /**
     * @var DomDocument[]
     */
    protected static $configs = [];

    /**
     * AbstractFormElement constructor.
     */
    public function __construct()
    {
        $this->config = FormElementConfig::of(get_class($this));
    }

    public function getIdPattern()
    {
        return (new \ReflectionClass($this))->getShortName() . "%s";
    }

    abstract public function isOrigin($any);

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return UXNode
     */
    abstract public function createElement();

    public function addToLayout($layout, $node, $screenX, $screenY)
    {
        // nop.
    }

    public function getLayoutChildren($layout)
    {
        return [];
    }

    public function isLayout()
    {
        return false;
    }

    /**
     * @param $node
     *
     * @return mixed
     */
    public function getTarget($node)
    {
        return $node;
    }

    public function applyProperties(UXDesignProperties $properties)
    {
        if ($this->config) {
            foreach ($this->config->getPropertyGroups() as $code => $group) {
                $properties->addGroup($code, $group['title']);
            }

            foreach ($this->config->getProperties() as $code => $property) {
                $editorFactory = $property['editorFactory'];
                $editor = $editorFactory();

                if ($editor) {
                    $properties->addProperty($property['group'], $property['code'], $property['name'], $editor);
                }
            }
        } else {
            Logger::warn("Cannot apply properties, config is empty, element = " . get_class($this));
        }
    }

    /**
     * @param UXDesignProperties $properties
     */
    public function createProperties(UXDesignProperties $properties)
    {
        $this->applyProperties($properties);
    }

    /**
     * @return null|string|UXImage
     */
    public function getIcon()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getInitProperties()
    {
        return $this->config ? $this->config->getInitProperties() : [];
    }

    public function getEventTypes()
    {
        return $this->config ? $this->config->getEventTypes() : [];
    }

    public function getGroup()
    {
        return 'Главное';
    }

    public function getDefaultSize()
    {
        $size = [150, 30];

        if ($this->initProperties['width']) {
            $size[0] = (int) $this->initProperties['width']['value'];
        }

        if ($this->initProperties['height']) {
            $size[1] = (int) $this->initProperties['height']['value'];
        }

        return $size;
    }

    public function refreshNode(UXNode $node)
    {
        // nop.
    }

    /**
     * @param $node
     * @return mixed
     */
    public function registerNode(UXNode $node)
    {
        return null;
    }
}