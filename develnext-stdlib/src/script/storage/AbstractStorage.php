<?php
namespace script\storage;

use php\gui\framework\AbstractScript;

abstract class AbstractStorage extends AbstractScript
{
    abstract public function load();
    abstract public function save();

    protected function applyImpl($target)
    {
        $this->load();
    }
}