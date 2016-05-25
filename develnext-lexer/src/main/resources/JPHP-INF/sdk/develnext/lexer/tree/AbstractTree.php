<?php
namespace develnext\lexer\tree;

/**
 * Class AbstractTree
 * @package develnext\lexer\tree
 */
abstract class AbstractTree
{

    /**
     * @return AbstractTree
     */
    public function getParent()
    {
    }

    /**
     * @param $index
     * @return AbstractTree
     */
    public function getChild($index)
    {
    }

    /**
     * @return int
     */
    public function getChildCount()
    {
    }
}