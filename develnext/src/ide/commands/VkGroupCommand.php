<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use php\gui\UXDesktop;

class VkGroupCommand extends AbstractCommand
{
    public function getName()
    {
        return _('menu.help.vk');
    }

    public function isAlways()
    {
        return true;
    }

    public function getCategory()
    {
        return 'help';
    }

    public function getIcon()
    {
        return 'icons/vk16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $desk = new UXDesktop();
        $desk->browse('http://vk.com/develnextstudio');
    }
}