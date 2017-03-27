<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use php\gui\UXDesktop;

class YoutubeChannelCommand extends AbstractCommand
{
    public function getName()
    {
        return _('menu.help.youtube');
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
        return 'icons/youtube16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $desk = new UXDesktop();
        $desk->browse('http://www.youtube.com/c/DevelNextOfficial');
    }
}