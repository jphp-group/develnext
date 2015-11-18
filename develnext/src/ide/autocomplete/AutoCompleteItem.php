<?php
namespace ide\autocomplete;

/**
 * Class AutoCompleteItem
 * @package ide\autocomplete
 */
abstract class AutoCompleteItem
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var null
     */
    protected $insert = null;

    /**
     * @param $name
     * @param string $description
     * @param null $insert
     */
    public function __construct($name, $description = '', $insert = null)
    {
        $this->name = $name;
        $this->description = $description;

        $this->insert = $insert ? $insert : $name;
    }

    public function getInsert()
    {
        return $this->insert;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}