<?php
namespace ide\editors;
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
     * AbstractEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    abstract public function isValid();

    abstract public function load();
    abstract public function save();

    /**
     * @return UXNode
     */
    abstract public function makeUi();
}