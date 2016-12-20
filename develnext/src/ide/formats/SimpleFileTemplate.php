<?php
namespace ide\formats;

use php\lib\fs;

class SimpleFileTemplate extends AbstractFileTemplate
{
    /**
     * @var array
     */
    private $args;

    public function __construct($source, array $args, $encoding = 'UTF-8')
    {
        $this->template = fs::get($source, $encoding);
        $this->args = $args;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->args;
    }
}