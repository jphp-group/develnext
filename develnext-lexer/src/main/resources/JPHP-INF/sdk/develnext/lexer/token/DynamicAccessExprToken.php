<?php
namespace develnext\lexer\token;

/**
 * Class DynamicAccessExprToken
 * @package develnext\lexer\token
 */
class DynamicAccessExprToken extends SimpleToken
{
    /**
     * @return SimpleToken
     */
    public function getField()
    {
    }

    /**
     * @return ExprStmtToken
     */
    public function getFieldExpr()
    {
    }
}