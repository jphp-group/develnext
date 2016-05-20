<?php
namespace develnext\lexer;


class Token
{
    /**
     * @readonly
     * @var int
     */
    public $type;

    /**
     * @readonly
     * @var string
     */
    public $text;

    /**
     * @readonly
     * @var int
     */
    public $line;

    /**
     * @readonly
     * @var int
     */
    public $position;

    /**
     * @readonly
     * @var int
     */
    public $channel;

    /**
     * @readonly
     * @var int
     */
    public $startIndex;

    /**
     * @readonly
     * @var int
     */
    public $stopIndex;

    /**
     * @readonly
     * @var int
     */
    public $tokenIndex;

    /**
     * @param int $type
     * @param null|string $text
     */
    public function __construct($type, $text = null)
    {
    }
}