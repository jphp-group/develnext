<?php
namespace ide\editors\common;

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
}