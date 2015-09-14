<?php
namespace ide\editors\argument;

class IntegerArgumentEditor extends MixedArgumentEditor
{
    public function getCode()
    {
        return "integer";
    }

    protected function valueTypes()
    {
        return ['integer', 'float', 'object', 'variable', 'expr'];
    }
}