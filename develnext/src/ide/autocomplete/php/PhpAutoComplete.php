<?php
namespace ide\autocomplete\php;

use develnext\lexer\inspector\AbstractInspector;
use ide\autocomplete\AutoComplete;
use ide\Logger;
use php\lib\Items;
use phpx\parser\SourceToken;
use phpx\parser\SourceTokenizer;

class PhpAutoComplete extends AutoComplete
{
    /**
     * ...
     */
    function __construct(AbstractInspector $inspector)
    {
        parent::__construct($inspector);

        $this->registerTypeRule(new PhpBasicAutoCompleteTypeRule($this));
        $this->registerTypeLoader(new PhpAutoCompleteLoader($inspector));
    }

    public function parsePrefixForRule($prefix)
    {
        //Logger::debug("Parse prefix for rule: $prefix");

        $tokens = SourceTokenizer::parseAll("<? " . $prefix);

        if (is_array($tokens) && sizeof($tokens)) {
            Items::shift($tokens);
        }

        $result = [];

        if ($tokens) {
            /** @var SourceToken $token */
            while ($token = Items::pop($tokens)) {
                /*if ($token->isOperatorToken()) {
                    switch ($token->type) {
                        case 'StaticAccessExprToken':
                        case 'DynamicAccessExprToken':
                            continue;
                    }

                    break;
                } */

                $result[] = $token;
            }
        } else {
            return [];
        }

        return Items::reverse($result);
    }
}