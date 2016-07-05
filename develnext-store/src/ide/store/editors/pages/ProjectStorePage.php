<?php
namespace ide\store\editors\pages;


use php\gui\layout\UXAnchorPane;
use php\gui\UXButton;
use php\gui\UXNode;

class ProjectStorePage extends AbstractStorePage
{
    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        return new UXButton('Click!');
    }

    public function refresh()
    {

    }

    function getName()
    {
        return "Проекты";
    }

    function getDescription()
    {
        return "Публичные проекты";
    }

    function getIcon()
    {
        return 'icons/sharedProject16.png';
    }

    function getBigIcon()
    {
        return 'icons/sharedProject24.png';
    }
}