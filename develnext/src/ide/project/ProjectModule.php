<?php
namespace ide\project;

/**
 * Class ProjectModule
 * @package ide\project
 */
class ProjectModule
{
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