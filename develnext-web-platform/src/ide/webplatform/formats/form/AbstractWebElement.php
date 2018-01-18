<?php
namespace ide\webplatform\formats\form;


use ide\formats\form\AbstractFormElement;
use php\gui\UXNode;
use php\lib\reflect;

/**
 * Class AbstractWebElement
 * @package ide\webplatform\formats\form
 */
abstract class AbstractWebElement extends AbstractFormElement
{
    /**
     * @return string
     */
    abstract public function uiSchemaClassName(): string;


    /**
     * @return UXNode
     */
    abstract public function createViewElement(): UXNode;

    /**
     * @return array
     */
    public function uiStylesheets(): array
    {
        return [];
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        foreach (['id', 'x', 'y', 'size'] as $prop) {
            if (isset($uiSchema[$prop])) {
                $view->{$prop} = $uiSchema[$prop];
            }
        }
    }

    public function uiSchema(UXNode $view): array
    {
        $schema = ['_' => $this->uiSchemaClassName()];

        $schema['id'] = $view->id;
        $schema['x'] = (int) $view->x;
        $schema['y'] = (int) $view->y;
        $schema['size'] = [(int) $view->width, (int) $view->height];
        $schema['style'] = $view->data('--style');

        if ($view->opacity < 1) {
            $schema['opacity'] = $view->opacity;
        }

        return $schema;
    }

    public function isOrigin($any)
    {
        if ($any instanceof UXNode) {
            $element = $any->data('--web-element');

            if ($element === $this) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $view = $this->createViewElement();
        $view->data('--web-element', $this);
        return $view;
    }
}