<?php
namespace ide\library;
use ide\entity\ProjectSkin;
use ide\Ide;
use ide\Logger;
use php\compress\ZipException;
use php\compress\ZipFile;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Configuration;

/**
 * Class IdeLibraryQuestResource
 * @package ide\library
 */
class IdeLibrarySkinResource extends IdeLibraryResource
{
    /**
     * @var ProjectSkin
     */
    private $skin;

    public function __construct($path = null)
    {
        $this->path = $path;

        $skinFile = new File("$path.zip");
        $this->config = new Configuration();

        if (fs::isFile($skinFile)) {
            try {
                $this->skin = ProjectSkin::createFromZip($skinFile);
                $this->valid = true;
            } catch (IOException | ZipException $e) {
                Logger::error("Failed to read skin, {$e->getMessage()}");
                $this->valid = false;
            }
        } else {
            $this->valid = false;
        }
    }

    public function delete()
    {
        if (!fs::delete("{$this->path}.zip")) {
            Logger::error("Failed to delete skin resource file {$this->path}.zip");
        }
    }

    public function getUniqueId()
    {
        return str::replace(str::replace(
            str::lower(fs::name($this->path)), ' ', '_'
        ), '-', '_');
    }

    /**
     * @return ProjectSkin
     */
    public function getSkin(): ?ProjectSkin
    {
        return $this->skin;
    }

    function isValid()
    {
        return parent::isValid() && $this->skin && !$this->skin->isEmpty();
    }


    /**
     * @return string
     */
    public function getCategory()
    {
        return 'skins';
    }


    /**
     * @param IdeLibrary $library
     */
    public function onRegister(IdeLibrary $library)
    {

    }
}