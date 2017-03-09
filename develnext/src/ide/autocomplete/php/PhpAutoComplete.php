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
}