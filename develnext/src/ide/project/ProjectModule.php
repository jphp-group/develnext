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

    /**
     * ProjectModule constructor.
     * @param string $id
     * @param string $type
     */
    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
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
     * @return string
     */
    public function getUniqueId()
    {
        return "$this->type@$this->id";
    }
}