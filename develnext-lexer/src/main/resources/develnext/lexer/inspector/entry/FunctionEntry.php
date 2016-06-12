<?php
namespace develnext\lexer\inspector\entry;

class FunctionEntry extends AbstractEntry
{
    public $name;
    public $namespace = null;
    public $fulledName = null;

    /**
     * @var ArgumentEntry[]
     */
    public $arguments = [];
    public $modifier = 'PUBLIC';

    public $data = [];
}