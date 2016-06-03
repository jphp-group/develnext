<?php
namespace develnext\lexer\token;

/**
 * Class ArgumentStmtToken
 * @package develnext\lexer\token
 */
class ArgumentStmtToken extends SimpleToken
{
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
    }

    /**
     * @return ExprStmtToken|null
     */
    public function getValue()
    {
    }

    /**
     * @return string ANY, STRING, INT, DOUBLE, NUMBER, BOOLEAN, SCALAR, ARRAY, OBJECT, CALLABLE, VARARG, TRAVERSABLE
     */
    public function getHintType()
    {
    }

    /**
     * @return NameToken
     */
    public function getHintTypeClass()
    {
    }
}