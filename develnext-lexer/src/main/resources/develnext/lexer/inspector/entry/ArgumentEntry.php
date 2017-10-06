<?php
namespace develnext\lexer\inspector\entry;

/**
 * Class ArgumentEntry
 * @package develnext\lexer\inspector\entry
 */
class ArgumentEntry extends AbstractEntry
{
    public $name;
    public $value;
    public $type;

    public $optional = false;
    public $nullable = false;
}