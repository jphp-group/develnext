<?php
namespace develnext\lexer\inspector\entry;

class ExtendTypeEntry extends AbstractEntry
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
     * @param array $data
     */
    public function __construct($type, array $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }
}