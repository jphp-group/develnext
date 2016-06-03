<?php
namespace develnext\lexer;

use php\io\IOException;
use php\io\Stream;

class Context
{
    /**
     * @param Stream|string $source
     * @param string $moduleName
     * @param string $charset
     */
    public function __construct($source, $moduleName = null, $charset = 'UTF-8')
    {
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
    }

    /**
     * @return string
     * @throws IOException
     */
    public function getContent()
    {
    }
}