<?php
namespace ide\library;
use Files;
use ide\Ide;
use ide\Logger;
use php\io\File;
use php\io\IOException;
use php\lang\IllegalStateException;
use php\lib\fs;
use php\lib\str;
use php\util\Configuration;
use ide\utils\FileUtils;

/**
 * Class IdeLibraryResource
 * @package ide\library
 */
abstract class IdeLibraryResource
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @return string
     */
    abstract public function getCategory();

    /**
     * IdeLibraryResource constructor.
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->path = $path;

        if ($path) {
            try {
                $this->config = new Configuration();
                $this->config->load($path . '.resource');
            } catch (IOException $e) {
                $this->config = new Configuration();
                Logger::warn("Unable to read '$path.resource' file - " . $e->getMessage());
            }
        } else {
            $this->config = new Configuration();
        }
    }

    public function save()
    {
        if ($this->path) {
            $this->config->save("{$this->path}.resource");
        } else {
            throw new IllegalStateException("Unable to save - path is empty");
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->config->get('description');
    }

    /**
     * @param $value
     */
    public function setDescription($value)
    {
        $this->config->set('description', $value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->config->get('name') ?: FileUtils::stripExtension(File::of($this->path)->getName());
    }

    /**
     * @param $name
     * @return string
     */
    public function setName($name)
    {
        return $this->config->set('name', $name);
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->config->get('author');
    }

    /**
     * @param $author
     */
    public function setAuthor($author)
    {
        $this->config->set('author', $author);
    }

    /**
     * @return string
     */
    public function getAuthorSite()
    {
        return $this->config->get('site');
    }

    /**
     * @param $site
     */
    public function setAuthorSite($site)
    {
        $this->config->set('site', $site);
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->config->get('version');
    }

    /**
     * @param $version
     */
    public function setVersion($version)
    {
        $this->config->set('version', $version);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function delete()
    {
        if (!fs::delete($this->getPath())) {
            Logger::error("Unable to delete {$this->getPath()}");
        }

        if (!fs::delete("{$this->getPath()}.resource")) {
            Logger::error("Unable to delete {$this->getPath()}.resource");
        }
    }

    /**
     * @return bool
     */
    public function isEmbedded()
    {
        return str::startsWith(FileUtils::hashName($this->path), FileUtils::hashName(Ide::get()->getOwnFile('')));
    }

    public function onRegister(IdeLibrary $library)
    {
        // ...
    }
}