<?php
namespace ide\webplatform\formats\form;

use framework\web\UIForm;
use ide\webplatform\editors\WebFormEditor;
use php\gui\UXLabel;
use php\gui\UXNode;

class FormWebElement extends AbstractWebElement
{
    public function isOrigin($any)
    {
        return $any instanceof UXNode && $any->data('--web-form');
    }


    public function getElementClass()
    {
        return UIForm::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return '';
    }

    /**
     * @return UXNode
     */
    public function createViewElement(): UXNode
    {
        return new UXLabel();
    }
}