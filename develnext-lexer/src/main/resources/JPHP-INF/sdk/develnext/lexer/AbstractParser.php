<?php
namespace develnext\lexer;
use develnext\lexer\tree\ParserRuleContext;

/**
 * Class AbstractParser
 * @package develnext\lexer
 */
abstract class AbstractParser
{
    /**
     * @param AbstractLexer $lexer
     */
    public function __construct(AbstractLexer $lexer)
    {
    }

    /**
     * ..
     */
    public function reset()
    {
    }

    /**
     * @return Token
     */
    public function consume()
    {
    }

    /**
     * @param $type
     * @return Token
     */
    public function match($type)
    {
    }

    /**
     * @return Token
     */
    public function matchWildcard()
    {
    }

    /**
     * @param bool $boolean
     */
    public function setBuildParseTree($boolean)
    {
    }

    /**
     * @return bool
     */
    public function getBuildParseTree()
    {
    }

    /**
     * @param bool $boolean
     */
    public function setTrimParseTree($boolean)
    {
    }

    /**
     * @return bool
     */
    public function getTrimParseTree()
    {
    }

    /**
     * @return int
     */
    public function getNumberOfSyntaxErrors()
    {
    }

    /**
     * @return Token
     */
    public function getCurrentToken()
    {
    }

    /**
     * @param ParserRuleContext $context
     * @param int $state
     * @param int $ruleIndex
     */
    public function enterRule(ParserRuleContext $context, $state, $ruleIndex)
    {
    }

    public function exitRule()
    {
    }

    /**
     * @param ParserRuleContext $context
     * @param int $altNum
     */
    public function enterOuterAlt(ParserRuleContext $context, $altNum)
    {
    }

    /**
     * @return int
     */
    public function getPrecedence()
    {
    }

    /**
     * @param ParserRuleContext $context
     * @param int $state
     * @param int $ruleIndex
     * @param int $precedence
     */
    public function enterRecursionRule(ParserRuleContext $context, $state, $ruleIndex, $precedence)
    {
    }

    /**
     * @param ParserRuleContext $context
     * @param int $state
     * @param int $ruleIndex
     */
    public function pushNewRecursionContext(ParserRuleContext $context, $state, $ruleIndex)
    {
    }

    /**
     *
     */
    public function unrollRecursionContexts()
    {
    }

    /**
     * @param int $ruleIndex
     * @return ParserRuleContext
     */
    public function getInvokingContext($ruleIndex)
    {
    }

    /**
     * @return ParserRuleContext
     */
    public function getContext()
    {
    }

    /**
     * @param ParserRuleContext $context
     */
    public function setContext(ParserRuleContext $context)
    {
    }

    /**
     * @param string $context
     * @return bool
     */
    public function inContext($context)
    {
    }

    /**
     * @param int $symbol
     * @return bool
     */
    public function isExpectedToken($symbol)
    {
    }

    /**
     * @return bool
     */
    public function isMatchedEOF()
    {
    }

    /**
     * @param string $ruleName
     * @return int
     */
    public function getRuleIndex($ruleName)
    {
    }

    /**
     * @return ParserRuleContext
     */
    public function getRuleContext()
    {
    }

    /**
     * @return string[]
     */
    public function getRuleInvocationStack()
    {
    }

    /**
     * @return string[]
     */
    public function getDFAStrings()
    {
    }

    public function dumpDFA()
    {
    }

    /**
     * @return string
     */
    public function getSourceName()
    {
    }

    /**
     * @param bool $boolean
     */
    public function setProfile($boolean)
    {
    }

    /**
     * @param bool $boolean
     */
    public function setTrace($boolean)
    {
    }

    /**
     * @return bool
     */
    public function isTrace()
    {
    }
}