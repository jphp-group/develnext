<?php
namespace develnext\lexer\inspector\entry;

use develnext\lexer\inspector\entry\ConstantEntry;
use develnext\lexer\inspector\entry\ExtendTypeEntry;
use develnext\lexer\inspector\entry\MethodEntry;
use develnext\lexer\inspector\entry\TypePropertyEntry;

class TypeEntry extends AbstractEntry
{
    public $name = '';
    public $fulledName = '';
    public $namespace = '';

    public $final = false;
    public $abstract = false;

    /**
     * @var ExtendTypeEntry[]
     */
    public $extends = [];

    /**
     * @var TypePropertyEntry[]
     */
    public $properties = [];

    /**
     * @var MethodEntry[]
     */
    public $methods = [];

    /**
     * @var ConstantEntry[]
     */
    public $constants = [];

    /**
     * @var array
     */
    public $data = [];
}