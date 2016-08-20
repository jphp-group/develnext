<?php
namespace ide\library;
use ide\bundle\AbstractBundle;
use ide\Logger;
use ide\utils\FileUtils;
use php\desktop\Runtime;
use php\lib\fs;

/**
 * Class IdeLibraryBundleResource
 * @package ide\library
 */
class IdeLibraryBundleResource extends IdeLibraryResource
{
    /**
     * @var AbstractBundle
     */
    protected $bundle;

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->config->get('group', 'other');
    }

    /**
     * @return string
     */
    public function getFullDescription()
    {
        $file = $this->config->get('descriptionFile', null);
        return $file ? FileUtils::get('res://' . $file) : null;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'bundles';
    }

    public function onRegister(IdeLibrary $library)
    {
        parent::onRegister($library);

        Runtime::addJar($this->getPath() . "/" . fs::name($this->getPath()) . ".jar");

        $class = $this->config->get('class');

        if (class_exists($class)) {
            $this->bundle = new $class();
            $this->bundle->setBundleDirectory($this->getPath());
            $this->bundle->onRegister($this);
        } else {
            throw new \Exception("Cannot register bundle resource, class '$class' is not found");
        }
    }

    /**
     * @return AbstractBundle
     */
    public function getBundle()
    {
        return $this->bundle;
    }
}