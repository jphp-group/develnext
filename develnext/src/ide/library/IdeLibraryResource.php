<?php
namespace ide\library;
use Files;
use ide\Ide;
use ide\Logger;
use ide\ui\MenuViewable;
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
abstract class IdeLibraryResource implements MenuViewable
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
     * @var bool
     */
    protected $valid;

    /**
     * @return string
     */
    abstract public function getCategory();

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return null;
    }

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
                $this->valid = true;
            } catch (IOException $e) {
                $this->config = new Configuration();
                Logger::warn("Unable to read '$path.resource' file - " . $e->getMessage());
                $this->valid = false;
            }
        } else {
            $this->config = new Configuration();
            $this->valid = false;
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
     * @return bool
     */
    public function isHidden()
    {
        return $this->config->get('hidden');
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
        return $this->config->get('name') ?: fs::pathNoExt(File::of($this->path)->getName());
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
        return $this->config->get('author', 'Unknown');
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
        return $this->config->get('version', '1.0');
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
        if (fs::isDir($this->getPath())) {
            if (!FileUtils::deleteDirectory($this->getPath())) {
                Logger::error("Unable to delete {$this->getPath()}");
            }
        } else {
            if (!fs::delete($this->getPath())) {
                Logger::error("Unable to delete {$this->getPath()}");
            }
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

    function getIcon()
    {
        return $this->config->get('icon');
    }

    function getMenuCount()
    {
        return -1;
    }

    /**
     * @return bool
     */
    function isValid()
    {
        return $this->valid;
    }
}