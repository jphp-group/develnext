<?php
namespace ide\formats\form;

use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use php\io\File;
use InvalidArgumentException;
use php\lib\Items;
use php\lib\Str;
use php\xml\DomDocument;
use php\xml\DomElement;
use php\xml\XmlProcessor;
use php\io\Stream;

/**
 * Class FormElementConfig
 * @package ide\formats\form
 */
class FormElementConfig
{
    /**
     * @var FormElementConfig[]
     */
    protected static $cache = [];

    /**
     * @var DomDocument
     */
    protected $document;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var FormElementConfig
     */
    protected $parent;

    /********* ... *********/

    /**
     * @var array
     */
    protected $initProperties = [];

    /**
     * @var array
     */
    protected $allInitProperties;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var array
     */
    protected $allProperties;

    /**
     * @var array
     */
    protected $propertyGroups = [];

    /**
     * @var array
     */
    protected $allPropertyGroups;

    /**
     * FormElementConfig constructor.
     *
     * @param $type
     */
    protected function __construct($type)
    {
        if (!Str::contains($type, '\\')) {
            $type = __NAMESPACE__ . '\\elements\\' . $type;
        }

        $file = 'res://' . Str::replace($type, '\\', '/') . '.xml';

        if (!Stream::exists($file)) {
            throw new InvalidArgumentException("XmlFileName($file) file is not exists");
        }

        $this->filename = $type;

        Stream::tryAccess($file, function (Stream $stream) {
            $processor = new XmlProcessor();
            $this->document = $processor->parse($stream);

            $this->readDocument();
        });
    }

    /**
     * @param $type
     *
     * @return FormElementConfig|null null if not exists
     */
    public static function of($type)
    {
        if ($instance = self::$cache[$type]) {
            return $instance;
        }

        try {
            return self::$cache[$type] = new FormElementConfig($type);
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return !!$this->parent;
    }

    /**
     * @return FormElementConfig
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return array [code, value, virtual: bool]
     */
    public function getInitProperties()
    {
        return $this->allInitProperties;
    }

    /**
     * @return array [code, name, group, editor: object]
     */
    public function getProperties()
    {
        return $this->allProperties;
    }

    /**
     * @return array [code, title]
     */
    public function getPropertyGroups()
    {
        return $this->allPropertyGroups;
    }


    protected function readDocument()
    {
        $extends = $this->document->get('/element/@extends');

        // extends.
        if ($extends) {
            $this->parent = FormElementConfig::of($extends);

            if ($this->parent == null) {
                throw new \Exception("Unable to find the 'extends' config of - $extends");
            }
        }

        $this->readInitPropertiesFromDocument();
        $this->readPropertiesFromDocument();

        $this->processParents();
    }


    protected function processParents()
    {
        $tree = [$this];
        $one = $this;

        while ($one->parent) {
            $tree[] = $one->parent;
            $one = $one->parent;
        }

        $tree = Items::reverse($tree);

        $initProperties = [];

        /** @var FormElementConfig $one */
        foreach ($tree as $one) {
            foreach ($one->initProperties as $code => $property) {
                $initProperties[$code] = $property;
            }
        }

        $properties = [];

        foreach ($tree as $one) {
            foreach ($one->properties as $code => $property) {
                $properties[$code] = $property;
            }
        }

        $propertyGroups = [];

        foreach ($tree as $one) {
            foreach ($one->propertyGroups as $code => $group) {
                $propertyGroups[$code] = $group;
            }
        }

        $this->allInitProperties = $initProperties;
        $this->allProperties = $properties;
        $this->allPropertyGroups = $propertyGroups;
    }

    protected function readInitPropertiesFromDocument()
    {
        // init properties
        foreach ($this->document->findAll('/element/init/property') as $property) {
            $code = $property->getAttribute('code');

            if (!$code) {
                throw new \Exception("Init property must have the 'code' value");
            }

            $this->initProperties[$code] = [
                'code'    => $code,
                'value'   => $property->getAttribute('value'),
                'virtual' => (bool)$property->getAttribute('virtual'),
            ];
        }
    }

    protected function readPropertiesFromDocument()
    {
        /** @var DomElement $groupProperties */
        foreach ($this->document->findAll('/element/properties') as $groupProperties) {
            $group = 'general';

            if ($groupProperties->hasAttribute('group')) {
                $group = $groupProperties->getAttribute('group');

                $this->propertyGroups[$group] = [
                    'code'  => $group,
                    'title' => $groupProperties->getAttribute('title'),
                ];
            }

            foreach ($groupProperties->findAll('property') as $property) {
                $code = $property->getAttribute('code');
                $name = $property->getAttribute('name');

                $editorFactory = function () use ($property) {
                    if ($property->hasAttribute('editor')) {
                        $editor = ElementPropertyEditor::getByCode($property->getAttribute('editor'))->unserialize($property);
                    } else {
                        $editor = (new SimpleTextPropertyEditor())->unserialize($property);
                    }

                    if ($property->getAttribute('virtual')) {
                        $editor->setAsDataProperty();
                    }

                    if ($property->getAttribute('css')) {
                        $editor->setAsCssProperty();
                    }

                    if ($property->getAttribute('formConfig')) {
                        $editor->setAsFormConfigProperty($property->getAttribute('defaultValue'));
                    }

                    $editor->setTooltip($property->getAttribute('tooltip'));

                    return $editor;
                };

                if (!$group) {
                    $group = 'general';
                }

                $this->properties[$code] = [
                    'code'          => $code,
                    'group'         => $group,
                    'name'          => $name,
                    'editorFactory' => $editorFactory,
                ];
            }
        }
    }
}