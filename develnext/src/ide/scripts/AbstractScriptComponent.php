<?php
namespace ide\scripts;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\FormElementConfig;
use ide\misc\GradleBuildConfig;
use php\gui\designer\UXDesignProperties;

/**
 * Class AbstractScriptComponent
 * @package ide\scripts
 */
abstract class AbstractScriptComponent
{
    /**
     * @var FormElementConfig
     */
    protected $config;

    /**
     * AbstractScriptComponent constructor.
     */
    public function __construct()
    {
        $this->config = FormElementConfig::of(get_class($this));
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @param GradleBuildConfig $gradleBuild
     *
     * @return array
     */
    public function adaptForGradleBuild(GradleBuildConfig $gradleBuild)
    {
        $gradleBuild->setDependency('develnext-stdlib');
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

    abstract  public function getDescription();

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
}