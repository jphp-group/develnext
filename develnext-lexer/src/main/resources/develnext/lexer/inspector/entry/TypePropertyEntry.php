<?php
namespace develnext\lexer\inspector\entry;

/**
 * Class TypePropertyEntry
 * @package develnext\lexer\inspector\entry
 */
class TypePropertyEntry
{
    public $name;
    public $modifier;
    public $value;

    public $static = false;

    /**
     * @var array
     */
    public $data = [];
}