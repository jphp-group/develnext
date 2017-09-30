<?php
namespace ide\project\behaviours\backup;

use ide\Ide;
use ide\misc\AbstractEntity;
use php\time\Time;

/**
 * Class Backup
 * @package ide\project\behaviours\backup
 */
class Backup extends AbstractEntity
{
    private $name;
    private $description;
    private $createdAt;
    private $filename;
    private $master;
    private $new = true;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return (int) $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = (int) $createdAt;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function getMaster()
    {
        return (bool) $this->master;
    }

    /**
     * @param bool $master
     */
    public function setMaster($master)
    {
        $this->master = (bool) $master;
    }

    /**
     * @return bool
     */
    public function isCreatedAtSession()
    {
        return new Time($this->getCreatedAt()) > Ide::get()->getStartTime();
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return (bool) $this->new;
    }

    /**
     * @param $value
     */
    public function setNew($value)
    {
        $this->new = $value;
    }
}