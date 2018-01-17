<?php
namespace ide\webplatform\project\behaviours\web;

class WebBootstrapScriptTemplate
{
    /**
     * @var string
     */
    protected $file;

    /**
     * WebBootstrapScriptTemplate constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }
}