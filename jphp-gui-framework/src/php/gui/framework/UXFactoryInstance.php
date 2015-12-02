<?php
namespace php\gui\framework;

use php\gui\UXCustomNode;
use php\gui\UXNode;

/**
 * Class UXFactoryInstance
 * @package php\gui\framework
 */
class UXFactoryInstance
{
    /**
     * @param UXCustomNode $node
     * @return UXNode
     */
    public function create(UXCustomNode $node)
    {
        $prototype = $node->get('prototype');
        return app()->create($prototype);
    }
}