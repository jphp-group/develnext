<?php
namespace develnext\lexer\inspector\entry;

/**
 * Class SnippetEntry
 * @package develnext\lexer\inspector\entry
 */
class SnippetEntry extends AbstractEntry
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $code;
}