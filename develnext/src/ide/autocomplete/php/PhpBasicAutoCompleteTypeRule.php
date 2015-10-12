<?php
namespace ide\autocomplete\php;

use ide\autocomplete\AutoCompleteTypeRule;
use php\lib\Items;

class PhpBasicAutoCompleteTypeRule extends AutoCompleteTypeRule
{
    public function identifyType($string)
    {
        /** @var PhpAutoComplete $complete */
        $complete = $this->complete;

        $tokens = $complete->parsePrefixForRule($string);

        if (sizeof($tokens) == 1) {
            $token = Items::first($tokens);

            switch ($token->name) {
                case 'StringExprToken':
                    return '~string';
                case 'NameToken':
                    return '~class ' . $token->word;
            }
        }

        return null;
    }
}