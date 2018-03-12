<?php
namespace ide\project;
use ide\utils\FileUtils;
use php\lib\fs;

/**
 * Class ProjectModule
 * @package ide\project
 */
class ProjectModule
{
    const TYPE_DIR = 'dir';

    protected $id;
    protected $type;
    protected $provided;

    /**
     * ProjectModule constructor.
     * @param string $id
     * @param string $type
     * @param bool $provided
     */
    public function __construct($id, $type, bool $provided = false)
    {
        $this->id = $id;
        $this->type = $type;
        $this->provided = $provided;
    }

    /**
     * @param $dirName
     * @param bool $provided
     * @return ProjectModule
     */
    public static function ofDir($dirName, bool $provided = false): ProjectModule
    {
        if (fs::isDir($dirName)) {
            $dirName = fs::abs($dirName);
        }

        return new ProjectModule(FileUtils::hashName($dirName), self::TYPE_DIR, $provided);
    }

    /**
     * @return bool
     */
    public function isDir(): bool
    {
        return $this->type === self::TYPE_DIR;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isProvided(): bool
    {
        return $this->provided;
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return "$this->type@$this->id";
    }
}