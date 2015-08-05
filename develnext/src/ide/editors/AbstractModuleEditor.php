<?php
namespace ide\editors;

abstract class AbstractModuleEditor extends AbstractEditor
{
    public function __construct($file)
    {
        parent::__construct($file);
    }
}