<?php
namespace ide\autocomplete;
use php\gui\UXImage;
use php\gui\UXImageArea;

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
     * @var string|UXImage|UXImageArea
     */
    protected $icon;

    /**
     * @var null
     */
    protected $insert = null;

    /**
     * @param $name
     * @param string $description
     * @param null $insert
     * @param null $icon
     */
    public function __construct($name, $description = '', $insert = null, $icon = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->icon = $icon;

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

    /**
     * @return \php\gui\UXImage|\php\gui\UXImageArea|string
     */
    public function getIcon()
    {
        return $this->icon;
    }
}