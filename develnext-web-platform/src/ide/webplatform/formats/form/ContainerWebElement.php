<?php
namespace ide\webplatform\formats\form;

use php\gui\layout\UXPanel;
use php\gui\UXNode;

/**
 * Class ContainerWebElement
 * @package ide\webplatform\formats\form
 */
abstract class ContainerWebElement extends AbstractWebElement
{
    public function getGroup()
    {
        return 'Панели';
    }

    public function isLayout()
    {
        return true;
    }

    public function addToLayout($self, $node, $screenX = null, $screenY = null)
    {
        /** @var UXPanel $self */
        if (isset($screenX) && isset($screenY)) {
            $node->position = $self->screenToLocal($screenX, $screenY);
        }

        $self->add($node);
    }

    public function getLayoutChildren($layout)
    {
        $children = flow($layout->children)
            ->find(function (UXNode $it) { return !$it->classes->has('x-system-element'); })
            ->toArray();

        return $children;
    }
}