<?php
namespace ide\formats\form;

use develnext\lexer\inspector\entry\TypeEntry;
use ide\behaviour\AbstractBehaviourSpec;
use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\library\IdeLibraryScriptGeneratorResource;
use ide\Logger;
use ide\utils\UiUtils;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXDragEvent;
use php\gui\framework\DataUtils;
use php\gui\UXImage;
use php\gui\UXNode;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lib\Items;
use php\lib\str;
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

    public function canBePrototype()
    {
        return !$this->isLayout();
    }

    /**
     * @return IdeLibraryScriptGeneratorResource[]
     */
    public function getScriptGenerators()
    {
        return [];
    }

    abstract public function isOrigin($any);

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return null
     */
    public function getElementClass()
    {
        return null;
    }

    /**
     * @return UXNode
     */
    abstract public function createElement();

    /**
     * Get data to index element.
     * @param UXNode $node
     * @return array
     */
    public function getIndexData(UXNode $node)
    {
        return [];
    }


    /**
     * Get preview image of element from index data.
     * @param array $indexData
     * @return null|UXImage
     */
    public function getCustomPreviewImage(array $indexData)
    {
        return null;
    }

    /**
     * @return AbstractBehaviourSpec[]
     */
    public function getInitialBehaviours()
    {
        return [];
    }

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
    public function getProperties()
    {
        return $this->config ? $this->config->getProperties() : [];
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

    public function findEventType($codeOrBind)
    {
        $types = $this->getEventTypes();

        if ($type = $types[$codeOrBind]) {
            return $type;
        }

        if ($type = $types[str::split($codeOrBind, '-')[0]]) {
            return $type;
        }

        $tmp = str::split($codeOrBind, '.', 2);

        if ($type = $types[str::split($tmp[1], '-')[0]]) {
            return $type;
        }

        return null;
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

    public function refreshNode(UXNode $node, UXDesigner $designer)
    {
        // nop.
    }

    /**
     * @param $node
     * @return mixed
     */
    public function registerNode(UXNode $node)
    {
        $classes = [];

        foreach (str::split($node->classesString, ' ') as $class) {
            $class = str::trim($class);
            $classes[$class] = $class;
        }

        uiLater(function () use ($node, $classes) {
            $node->classes->clear();
            $node->classes->addAll($classes);
        });

        return null;
    }

    public function unregister()
    {
        // nop.
    }

    public function canDragDrop(UXDragEvent $e, UXNode $parent = null)
    {
        return false;
    }

    public function canDragDropIn(UXDragEvent $e)
    {
        return false;
    }

    public function dragDrop(UXDragEvent $e, UXNode $node, UXNode $parent = null)
    {
    }

    public function dragDropIn(UXDragEvent $e, UXNode $node)
    {
    }

    public function refreshInspector(UXNode $node, TypeEntry $type)
    {
    }

    public function designHasBeenChanged(UXNode $node, UXDesigner $designer)
    {
        // nop.
    }
}