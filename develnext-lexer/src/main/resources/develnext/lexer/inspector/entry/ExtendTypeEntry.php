<?php
namespace develnext\lexer\inspector\entry;

class ExtendTypeEntry
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $data = [];

    /**
     * ExtendTypeEntry constructor.
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }
}