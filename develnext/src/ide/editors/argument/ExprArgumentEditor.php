<?php
namespace ide\editors\argument;

class ExprArgumentEditor extends MixedArgumentEditor
{
    public function getCode()
    {
        return "expr";
    }

    protected function valueTypes()
    {
        return ['expr', 'variable', 'object'];
    }
}