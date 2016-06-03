<?php
namespace develnext\lexer;
use develnext\lexer\token\SimpleToken;
use php\io\IOException;

/**
 * Class Tokenizer
 * @package develnext\lexer
 */
class Tokenizer
{
    /**
     * @param Context $context
     * @throws IOException
     */
    public function __construct(Context $context)
    {
    }

    /**
     * @return Context
     */
    public function getContext()
    {
    }

    /**
     * @return SimpleToken
     */
    public function nextToken()
    {
    }

    /**
     * @return SimpleToken[]
     */
    public function fetchAll()
    {
    }

    /**
     * ...
     */
    public function reset()
    {
    }
}