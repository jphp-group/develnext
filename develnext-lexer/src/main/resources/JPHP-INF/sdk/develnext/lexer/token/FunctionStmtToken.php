<?php
namespace develnext\lexer\token;

/**
 * Class FunctionStmtToken
 * @package develnext\lexer\token
 */
class FunctionStmtToken extends SimpleToken
{
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getShortName()
    {
    }

    /**
     * @return string
     */
    public function getFulledName()
    {
    }

    /**
     * @return string PUBLIC, PROTECTED, PRIVATE
     */
    public function getModifier()
    {
    }

    /**
     * @return string
     */
    public function getComment()
    {
    }

    /**
     * @return ArgumentStmtToken[]
     */
    public function getArguments()
    {
    }
}