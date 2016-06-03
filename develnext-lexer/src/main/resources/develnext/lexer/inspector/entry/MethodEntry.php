<?php
namespace develnext\lexer\inspector\entry;

/**
 * Class MethodEntry
 * @package develnext\lexer\inspector\entry
 */
class MethodEntry extends FunctionEntry
{
    public $final = false;
    public $static = false;
    public $abstract = false;
    public $interfacable = false;
}