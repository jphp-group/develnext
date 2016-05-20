<?php
namespace develnext\lexer;

/**
 * Class AbstractLexer
 * @package develnext\lexer
 */
abstract class AbstractLexer
{
    /**
     * @readonly
     * @var string
     */
    public $sourceName;

    /**
     * @readonly
     * @var string
     */
    public $grammarFileName;

    /**
     * @readonly
     * @var string[]
     */
    public $modeNames = [];

    /**
     * @var string
     */
    public $text = '';

    /**
     * @var int
     */
    public $type = 0;

    /**
     * @var int
     */
    public $line;

    /**
     * @var int
     */
    public $position;

    /**
     * @var int
     */
    public $state;

    /**
     * @var int
     */
    public $channel;

    /**
     * @var int
     */
    public $charIndex;

    /**
     * @var Token
     */
    public $token;

    /**
     * Reset all.
     */
    public function reset()
    {
    }

    /**
     * nstruct the lexer to skip creating a token for current lexer rule and look for another token.
     * nextToken() knows to keep looking when a lexer rule finishes with token set to SKIP_TOKEN.
     * Recall that if token==null at end of any token rule, it creates one for you and emits it.
     */
    public function skip()
    {
    }

    /**
     * ...
     */
    public function more()
    {
    }

    /**
     *
     */
    public function emitEOF()
    {
    }

    /**
     * The standard method called to automatically emit a token at the outermost lexical rule.
     * The token object should point into the char buffer start..stop. If there is a text override in 'text',
     * use that to set the token's text. Override this method to emit custom Token objects or provide a new factory.
     *
     * @param Token $token (optional) By default does not support multiple emits per nextToken invocation for efficiency reasons.
     *                              Subclass and override this method, nextToken, and getToken
     *                              (to push tokens into a list and pull from that list rather than a single variable as this implementation does).
     * @return Token|void
     */
    public function emit(Token $token)
    {
    }

    /**
     * Return a token from this source; i.e., match a token on the char stream.
     * @return Token
     */
    public function nextToken()
    {
    }

    /**
     * @param int $mode
     */
    public function pushMode($mode)
    {
    }

    /**
     * @return int
     */
    public function popMode()
    {
    }
}