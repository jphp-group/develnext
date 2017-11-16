<?php
namespace develnext\lexer;

use develnext\lexer\token\ClassStmtToken;
use develnext\lexer\token\FunctionStmtToken;
use develnext\lexer\token\SimpleToken;
use php\lang\Environment;

/**
 * Class SyntaxAnalyzer
 * @package develnext\lexer
 */
class SyntaxAnalyzer
{
    public function __construct(Environment $env, Tokenizer $tokenizer)
    {
    }

    /**
     * @return Context
     */
    public function getContext()
    {
    }

    /**
     * ...
     */
    public function reset(Environment $env, Tokenizer $tokenizer)
    {
    }

    /**
     * @return Token[]
     */
    public function getTree()
    {
    }

    /**
     * @return ClassStmtToken[]
     */
    public function getClasses()
    {
    }

    /**
     * @return FunctionStmtToken[]
     */
    public function getFunctions()
    {
    }

    /**
     * @param $expression
     * @param bool $shortExpr
     * @return SimpleToken[]
     */
    public static function analyzeExpressionForDetectType($expression, $shortExpr = false)
    {
    }

    /**
     * @param string $name
     * @param SimpleToken $owner
     * @param string $type CLASS, FUNCTION, CONSTANT
     * @return string
     */
    public static function getRealName($name, SimpleToken $owner, $type)
    {
    }
}