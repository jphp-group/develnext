<?php
namespace ide\autocomplete\php;

use ide\autocomplete\AutoComplete;
use php\lib\Items;
use phpx\parser\SourceToken;

class PhpAutoComplete extends AutoComplete
{
    function __construct()
    {
        $this->registerTypeRule(new PhpBasicAutoCompleteTypeRule($this));
    }

    public function parsePrefixForRule($prefix)
    {
        $tokens = SourceTokenizer::parseAll($prefix);

        $result = [];

        /** @var SourceToken $token */
        while ($token = Items::pop($tokens)) {
            if ($token->isOperatorToken()) {
                switch ($token->type) {
                    case 'StaticAccessExprToken':
                    case 'DynamicAccessExprToken':
                        continue;
                }

                break;
            }

            $result[] = $token;
        }

        return Items::reverse($result);
    }
}