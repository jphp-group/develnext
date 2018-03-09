<?php
namespace develnext\lexer\token;
use develnext\lexer\Token;

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

    /**
     * @return VariableExprToken[]
     */
    public function getLocalVariables()
    {
    }

    /**
     * @return VariableExprToken[]
     */
    public function getStaticLocalVariables()
    {
    }

    /**
     * @return ExpressionInfo
     */
    public function getTypeInfo(SimpleToken $token)
    {
    }

    /**
     * @return bool
     */
    public function isReturnOptional()
    {
    }

    /**
     * @return string
     */
    public function getReturnHintType()
    {
    }

    /**
     * @return NameToken
     */
    public function getReturnHintTypeClass()
    {
    }
}