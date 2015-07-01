<?php
namespace ide\editors;
use ide\formats\AbstractFormat;
use php\gui\layout\UXPane;
use php\gui\UXNode;

/**
 * Class AbstractEditor
 * @package ide\editors
 */
abstract class AbstractEditor
{
    /** @var string */
    protected $file;

    /**
     * @var AbstractFormat
     */
    protected $format;

    /**
     * AbstractEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param AbstractFormat $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return AbstractFormat
     */
    public function getFormat()
    {
        return $this->format;
    }

    abstract public function load();
    abstract public function save();

    public function getTitle()
    {
        return $this->format->getTitle($this->file);
    }

    public function getIcon()
    {
        return $this->format->getIcon();
    }

    public function getTooltip()
    {
        return null;
    }

    /**
     * @return UXNode
     */
    abstract public function makeUi();
}