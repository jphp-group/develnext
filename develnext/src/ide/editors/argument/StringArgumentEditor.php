<?php
namespace ide\editors\argument;

class StringArgumentEditor extends MixedArgumentEditor
{
    public function getCode()
    {
        return "string";
    }

    protected function valueTypes()
    {
        return ['string', 'magicString', 'object', 'score', 'variable', 'expr'];
    }
}