<?php
namespace develnext\lexer\inspector\entry;

/**
 * Class ConstantEntry
 * @package develnext\lexer\inspector\entry
 */
class ConstantEntry extends AbstractEntry
{
    public $name;
    public $value;

    public $modifier = 'PUBLIC';
}