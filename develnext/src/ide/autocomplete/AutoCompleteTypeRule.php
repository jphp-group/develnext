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

    abstract public function identifyType($string, AutoCompleteRegion $region);

    /**
     * @param $sourceCode
     * @param int $caretPosition
     * @param int $caretLine
     * @param int $caretOffset
     * @return mixed
     */
    abstract public function updateStart($sourceCode, $caretPosition = 0, $caretLine = 0, $caretOffset = 0);

    /**
     * @param SourceTokenizer $tokenizer
     * @param SourceToken $token
     * @param SourceToken $previousToken
     * @return mixed
     */
    abstract public function update(SourceTokenizer $tokenizer, SourceToken $token, SourceToken $previousToken = null);

    /**
     * @param $sourceCode
     * @return mixed
     */
    abstract public function updateDone($sourceCode);
}