<?php
namespace ide\store\formats;

use ide\store\editors\StoreEditor;
use ide\editors\AbstractEditor;
use ide\formats\AbstractFormat;

class StoreFormat extends AbstractFormat
{
    /**
     * @param $file
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new StoreEditor($file);
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return $file == '~dn-store';
    }

    public function getIcon()
    {
        return 'icons/product16.png';
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {
    }
}