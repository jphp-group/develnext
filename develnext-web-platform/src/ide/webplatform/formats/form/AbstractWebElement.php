<?php
namespace ide\webplatform\formats\form;


use framework\web\ui\UINode;
use ide\formats\form\AbstractFormElement;
use php\gui\layout\UXAnchorPane;
use php\gui\UXNode;
use php\gui\UXParent;
use php\lib\reflect;
use php\lib\str;

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
     * @return string
     */
    public function getElementClass()
    {
        return UINode::class;
    }

    public function isNeedRegisterInSource()
    {
        return true;
    }

    /**
     * @return array
     */
    public function uiStylesheets(): array
    {
        return [];
    }

    public function loadUiSchema(UXNode $view, array $uiSchema)
    {
        foreach (['id', 'x', 'y'] as $prop) {
            if (isset($uiSchema[$prop])) {
                $view->{$prop} = $uiSchema[$prop];
            }
        }

        if (isset($uiSchema['width'])) {
            $view->{'webWidth'} = $uiSchema['width'];
        }

        if (isset($uiSchema['height'])) {
            $view->{'webHeight'} = $uiSchema['height'];
        }
    }

    public function uiSchema(UXNode $view): array
    {
        $schema = ['_' => $this->uiSchemaClassName()];

        $schema['id'] = $view->id;

        if ($view->parent instanceof UXAnchorPane) {
            $schema['x'] = (int)$view->x;
            $schema['y'] = (int)$view->y;
        }

        $schema['width'] = $view->{'webWidth'};
        $schema['height'] = $view->{'webHeight'};
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

        foreach (['width', 'height'] as $prop) {
            $webProp = 'web' . str::upperFirst($prop);

            $updateWidth = function ($onlyPercent = true) use ($view, $webProp, $prop) {
                if ($view->parent) {
                    $webWidth = $view->data($webProp);

                    if (str::endsWith($webWidth, '%')) {
                        $newWidth = $view->parent->{$prop} * (((int)$webWidth) / 100);

                        if ($newWidth !== $view->{$prop}) {
                            $view->{$prop} = $newWidth;
                        }
                    } else {
                        $view->{$prop} = (int)$webWidth;
                    }
                }
            };

            $view->observer('parent')->addListener(function ($_, $new) use ($updateWidth, $prop) {
                if ($new instanceof UXParent) {
                    $new->observer($prop)->addListener($updateWidth);
                    $updateWidth(true);
                }
            });

            $view->observer($prop)->addListener(function () use ($updateWidth) {
                $updateWidth();
            });

            $view->data("--property-$webProp-getter", function () use ($view, $webProp) {
                return $view->data($webProp);
            });

            $view->data("--property-$webProp-setter", function ($value) use ($view, $updateWidth, $webProp) {
                $view->data($webProp, $value);
                $updateWidth(false);
            });
        }

        $view->{'webWidth'} = $this->getDefaultSize()[0];
        $view->{'webHeight'} = $this->getDefaultSize()[1];

        return $view;
    }

    /**
     * @param array $schemaAlign
     * @return string
     */
    public static function schemaAlignToViewAlign(array $schemaAlign): string
    {
        $alignment = str::upper(str::join($schemaAlign, '_'));

        if ($alignment === 'CENTER_CENTER') {
            $alignment = 'CENTER';
        }

        return $alignment;
    }

    /**
     * @param string $viewAlign
     * @return array
     */
    public static function viewAlignToSchemaAlign(string $viewAlign): array
    {
        $align = ['center', 'center'];

        if (str::startsWith($viewAlign, 'TOP_')) {
            $align[0] = 'top';
        } else if (str::startsWith($viewAlign, 'BOTTOM_')) {
            $align[0] = 'bottom';
        }

        if (str::endsWith($viewAlign, '_LEFT')) {
            $align[1] = 'left';
        }

        if (str::endsWith($viewAlign, '_RIGHT')) {
            $align[1] = 'right';
        }

        $schema['align'] = $align;

        return $align;
    }
}