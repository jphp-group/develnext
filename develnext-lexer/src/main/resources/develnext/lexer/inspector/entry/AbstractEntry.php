<?php
namespace develnext\lexer\inspector\entry;


abstract class AbstractEntry
{
    public $token = null;

    public $startLine;
    public $startPosition;

    public $endLine;
    public $endPosition;
}