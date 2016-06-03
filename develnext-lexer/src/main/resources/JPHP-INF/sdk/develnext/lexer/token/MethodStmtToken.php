<?php
namespace develnext\lexer\token;

/**
 * Class MethodStmtToken
 * @package develnext\lexer\token
 */
class MethodStmtToken extends FunctionStmtToken
{
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
    }

    /**
     * @return bool
     */
    public function isInterfacable()
    {
    }
}