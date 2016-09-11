<?php
namespace ide\editors\common;

use ide\formats\form\AbstractFormElement;
use ide\Ide;
use php\gui\UXImageView;

class ObjectListEditorItem extends \stdClass
{
    /**
     * @var string
     */
    public $text;
    
    /**
     * @var UXImageView
     */
    public $graphic;
    
    /**
     * @var string|null
     */
    public $value;
    
    /**
     * @var int
     */
    public $level = 0;
    
    /**
     * @var string
     */
    public $hint;

    /**
     * @var string
     */
    public $prefix = null;

    /**
     * @var AbstractFormElement
     */
    public $element;
    
    /**
     * ListItem constructor.
     * @param $text
     * @param $graphic
     * @param null $value
     * @param int $level
     */
    public function __construct($text = null, $graphic = null, $value = null, $level = 0)
    {
        $this->text = $text;
        $this->graphic = $graphic;
        $this->value = $value !== null ? $value : $text;
        $this->level = $level;
    }
    
    function __toString()
    {
        return (string)($this->text);
    }

    public function duplicate()
    {
        $item = new ObjectListEditorItem($this->text, $this->graphic, $this->value, $this->level);
        $item->hint = $this->hint;
        $item->prefix = $this->prefix;
        $item->element = $this->element;

        return $item;
    }

    public function getName()
    {
        return $this->text;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getIcon()
    {
        return $this->graphic;
    }
}