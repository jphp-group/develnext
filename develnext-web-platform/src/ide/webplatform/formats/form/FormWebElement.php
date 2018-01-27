<?php
namespace ide\webplatform\formats\form;

use framework\web\UIForm;
use ide\webplatform\editors\WebFormEditor;
use ide\webplatform\formats\WebFormFormat;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\lib\reflect;

class FormWebElement extends AbstractWebElement
{
    public function isOrigin($any)
    {
        return $any instanceof WebFormEditor;
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