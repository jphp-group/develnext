<?php
namespace php\gui\designer;

use php\gui\UXControl;
use php\gui\UXPopupWindow;
use php\io\File;

class UXAbstractDirectoryTreeSource
{
    public function shutdown()
    {
    }

    /**
     * @param $path
     * @param $newName
     * @return string|null
     */
    public function rename($path, $newName)
    {
    }
}