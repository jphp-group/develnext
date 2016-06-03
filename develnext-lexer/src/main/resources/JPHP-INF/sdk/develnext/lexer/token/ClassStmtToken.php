<?php
namespace develnext\lexer\token;

/**
 * Class ClassStmtToken
 * @package develnext\lexer\token
 */
class ClassStmtToken extends SimpleToken
{
    /**
     * ...
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getFulledName()
    {
    }

    /**
     * @return string
     */
    public function getNamespaceName()
    {
    }

    /**
     * @return NameToken
     */
    public function getName()
    {
    }

    /**
     * @return string CLASS, TRAIT, INTERFACE
     */
    public function getClassType()
    {
    }

    /**
     * @return string PUBLIC, PRIVATE, PROTECTED
     */
    public function getModifier()
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
    public function isFinal()
    {
    }

    /**
     * @return string
     */
    public function getExtendName()
    {
    }

    /**
     * @return string[]
     */
    public function getImplementNames()
    {
    }

    /**
     * @return string[]
     */
    public function getUseNames()
    {
    }

    /**
     * @return string
     */
    public function getComment()
    {
    }

    /**
     * @return ConstStmtToken[]
     */
    public function getConstants()
    {
    }

    /**
     * @return ClassVarStmtToken[]
     */
    public function getProperties()
    {
    }

    /**
     * @return MethodStmtToken[]
     */
    public function getMethods()
    {
    }
}