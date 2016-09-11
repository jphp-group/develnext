<?php
namespace ide\marker\target;

use ide\forms\ActionConstructorForm;
use php\gui\UXNode;

/**
 * Class ActionTypeMarketTarget
 * @package ide\marker\target
 */
class ActionTypeMarketTarget extends AbstractMarkerTarget
{
    /**
     * @var ActionConstructorForm
     */
    protected $constructorForm;

    /**
     * @var string
     */
    protected $type;

    /**
     * ActionTypeMarketTarget constructor.
     * @param ActionConstructorForm $constructorForm
     * @param string $type
     */
    public function __construct(ActionConstructorForm $constructorForm, $type)
    {
        $this->constructorForm = $constructorForm;
        $this->type = $type;
    }

    /**
     * @return UXNode
     */
    function getMarkerNode()
    {
        return $this->constructorForm->getAndShowActionType($this->type);
    }
}