<?php
namespace develnext\lexer\token;

/**
 * Class ClassVarStmtToken
 * @package develnext\lexer\token
 */
class ClassVarStmtToken extends SimpleToken
{
    /**
     * @return string
     */
    public function getVariable()
    {
    }

    /**
     * @return ExprStmtToken
     */
    public function getValue()
    {
    }

    /**
     * @return string PUBLIC, PROTECTED, PRIVATE
     */
    public function getModifier()
    {
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
    }

    /**
     * @return string
     */
    public function getComment()
    {
    }
}