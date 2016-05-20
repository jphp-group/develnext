<?php
namespace ide\library;
use ide\bundle\AbstractBundle;
use ide\Logger;
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
    public function getCategory()
    {
        return 'bundles';
    }

    public function onRegister(IdeLibrary $library)
    {
        parent::onRegister($library);

        $class = $this->config->get('class');

        if (class_exists($class)) {
            $this->bundle = new $class();
            $this->bundle->setBundleDirectory($this->getPath());
        } else {
            throw new \Exception("Cannot register bundle resource, class '$class' is not found");
        }

        Runtime::addJar($this->getPath() . "/" . fs::name($this->getPath()) . ".jar");
    }

    /**
     * @return AbstractBundle
     */
    public function getBundle()
    {
        return $this->bundle;
    }
}