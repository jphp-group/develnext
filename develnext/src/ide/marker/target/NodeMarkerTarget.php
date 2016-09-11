<?php
namespace ide\marker\target;

use php\gui\UXNode;

class NodeMarkerTarget extends AbstractMarkerTarget
{
    /**
     * @var UXNode
     */
    protected $node;

    /**
     * NodeMarketTarget constructor.
     * @param UXNode $node
     */
    public function __construct(UXNode $node)
    {
        $this->node = $node;
    }

    /**
     * @return UXNode
     */
    function getMarkerNode()
    {
        return $this->node;
    }
}