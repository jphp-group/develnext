<?php
namespace ide\editors\action;
use php\xml\DomDocument;

/**
 * Class ActionContainer
 * @package ide\editors\action
 */
class ActionContainer
{
    /**
     * @var DomDocument
     */
    protected $document;

    /**
     * ActionContainer constructor.
     * @param DomDocument $document
     */
    public function __construct(DomDocument $document)
    {
        $this->document = $document;
    }
}