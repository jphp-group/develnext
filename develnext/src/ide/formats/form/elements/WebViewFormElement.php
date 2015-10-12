<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use ide\Ide;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXWebEvent;
use php\gui\UXNode;
use php\gui\UXTextField;
use php\gui\UXWebView;

class WebViewFormElement extends AbstractFormElement
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Web Браузер';
    }

    public function getGroup()
    {
        return 'Дополнительно';
    }

    public function getIcon()
    {
        return 'icons/webBrowser16.png';
    }

    public function getIdPattern()
    {
        return "browser%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $element = new UXWebView();

        Ide::get()->getMainForm()->toast('У браузера есть баги при открытии некоторых страниц');

        return $element;
    }

    public function getDefaultSize()
    {
        return [300, 300];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXWebView;
    }
}