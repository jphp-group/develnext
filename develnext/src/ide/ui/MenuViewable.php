<?php
namespace ide\ui;

interface MenuViewable
{
    function getName();
    function getDescription();
    function getIcon();

    function getMenuCount();
}