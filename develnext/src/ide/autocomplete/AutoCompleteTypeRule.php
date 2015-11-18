<?php
namespace ide\autocomplete;
use phpx\parser\SourceToken;
use phpx\parser\SourceTokenizer;

/**
 * Class AutoCompleteTypeRule
 * @package ide\autocomplete
 */
abstract class AutoCompleteTypeRule
{
    /**
     * @var AutoComplete
     */
    protected $complete;

    /**
     * AutoCompleteTypeRule constructor.
     * @param AutoComplete $complete
     */
    public function __construct(AutoComplete $complete)
    {
        $this->complete = $complete;
    }

    abstract public function identifyType($string);

    /**
     * @return mixed
     */
    abstract public function updateStart();

    /**
     * @param SourceTokenizer $tokenizer
     * @param SourceToken $token
     * @param SourceToken $previousToken
     * @return mixed
     */
    abstract public function update(SourceTokenizer $tokenizer, SourceToken $token, SourceToken $previousToken = null);

    /**
     * @return mixed
     */
    abstract public function updateDone();
}