<?php
namespace ide\editors\argument;

class FloatArgumentEditor extends MixedArgumentEditor
{
    public function getCode()
    {
        return "float";
    }

    protected function valueTypes()
    {
        return ['float', 'object', 'score', 'globalVariable', 'variable', 'expr'];
    }
}