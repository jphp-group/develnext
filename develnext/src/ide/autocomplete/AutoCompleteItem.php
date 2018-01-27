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
     * @var string
     */
    protected $content;

    /**
     * @var string|UXImage|UXImageArea
     */
    protected $icon;

    /**
     * @var null
     */
    protected $insert = null;

    /**
     * @var string
     */
    private $style;

    /**
     * @var AutoCompleteItem[]
     */
    private $subItems = [];

    /**
     * @param $name
     * @param string $description
     * @param null $insert
     * @param null $icon
     * @param null $style
     */
    public function __construct($name, $description = '', $insert = null, $icon = null, $style = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->icon = $icon;

        $this->insert = $insert !== null ? $insert : $name;
        $this->style = $style;
    }

    /**
     * @param AutoCompleteItem $item
     */
    public function addSubItem(AutoCompleteItem $item)
    {
        $this->subItems[] = $item;
    }

    /**
     * @return AutoCompleteItem[]
     */
    public function getSubItems(): array
    {
        return $this->subItems;
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

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    public function getDefaultIcon()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @param UXImage|UXImageArea|string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param string $insert
     */
    public function setInsert(?string $insert)
    {
        $this->insert = $insert;
    }
}