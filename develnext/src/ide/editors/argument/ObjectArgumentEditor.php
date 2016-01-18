<?php
namespace ide\editors\argument;

class ObjectArgumentEditor extends MixedArgumentEditor
{
    public function getCode()
    {
        return "object";
    }

    protected function valueTypes()
    {
        return ['object', 'instances', 'variable', 'expr'];
    }
}