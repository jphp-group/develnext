<?php
namespace ide\formats\form;

use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\formats\form\event\AbstractEventKind;
use ide\Logger;
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
     * @var array
     */
    protected $eventTypes = [];

    /**
     * @var array
     */
    protected $allEventTypes;

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
            Logger::warn($e->getMessage());

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
        return Items::sort($this->allPropertyGroups, function ($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }

            return $a['sort'] > $b['sort'] ? 1 : -1;
        }, true);
    }

    public function getEventTypes()
    {
        return $this->allEventTypes;
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
        $this->readEventTypesFromDocument();

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

        $eventTypes = [];

        /** @var FormElementConfig $one */
        foreach ($tree as $one) {
            foreach ($one->eventTypes as $code => $eventType) {
                $eventTypes[$code] = $eventType;
            }
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
        $this->allEventTypes = $eventTypes;
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
                    'sort'  => (int) $groupProperties->getAttribute('sort'),
                ];
            }

            foreach ($groupProperties->findAll('property') as $property) {
                $code = $property->getAttribute('code');
                $name = $property->getAttribute('name');

                $editorFactory = function () use ($property, $code) {
                    if ($property->hasAttribute('editor')) {
                        if ($property->getAttribute('editor') == 'none') {
                            $editor = null;
                        } else {
                            $editor = ElementPropertyEditor::getByCode($property->getAttribute('editor'))->unserialize($property);
                        }
                    } else {
                        $editor = (new SimpleTextPropertyEditor())->unserialize($property);
                    }

                    if ($editor) {
                        $realCode = $property->getAttribute('realCode');

                        if ($property->getAttribute('virtual')) {
                            $editor->setAsDataProperty($realCode);
                        }

                        if ($property->getAttribute('css')) {
                            $editor->setAsCssProperty($realCode);
                        }

                        if ($property->getAttribute('formConfig')) {
                            $editor->setAsFormConfigProperty($property->getAttribute('defaultValue'), $realCode);
                        }

                        if ($realCode) {
                            $tooltip = "[ ->$realCode ]";
                        } else {
                            $tooltip = "[ ->$code ]";
                        }

                        if ($property->getAttribute('tooltip')) {
                            $tooltip .= " {$property->getAttribute('tooltip')}";
                        }

                        $editor->setTooltip($tooltip);
                    }

                    return $editor;
                };

                if (!$group) {
                    $group = 'general';
                }

                $this->properties[$code] = [
                    'code'          => $code,
                    'group'         => $group,
                    'name'          => $name,
                    'editor'        => $property->getAttribute('editor'),
                    'editorFactory' => $editorFactory,

                    'tooltip'       => $property->getAttribute('tooltip'),
                    'realCode'      => $property->getAttribute('realCode'),

                    'isCss'         => $property->getAttribute('css'),
                    'isVirtual'     => $property->getAttribute('virtual'),
                    'isFormConfig'  => $property->getAttribute('formConfig'),
                ];
            }
        }
    }

    protected function readEventTypesFromDocument()
    {
        /** @var DomElement $eventType */
        foreach ($this->document->findAll("/element/eventTypes/eventType") as $eventType) {
            $code = $eventType->getAttribute('code');
            $name = $eventType->getAttribute('name');
            $description = $eventType->getAttribute('description');

            $icon = $eventType->getAttribute('icon');
            $kind = $eventType->getAttribute('kind');
            $idParameter = $eventType->getAttribute('idParameter');

            $kind = "ide\\formats\\form\\event\\{$kind}Kind";

            /** @var AbstractEventKind $kind */
            $kind = new $kind();

            $this->eventTypes[$code] = [
                'code'        => $code,
                'name'        => $name,
                'description' => $description,
                'icon'        => $icon,
                'kind'        => $kind,
                'idParameter' => $idParameter,
                'separator'   => !!$eventType->getAttribute('separator')
            ];

            $paramVariants = [];

            /** @var DomElement $it */
            foreach ($eventType->findAll("variants/variant") as $it) {
                $paramVariants[str::trim($it->getTextContent())] = $it->getAttribute('value');
            }

            $kind->setParamVariants($paramVariants);
        }
    }
}