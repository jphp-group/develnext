<?php
namespace ide\formats;

use ide\editors\FactoryEditor;
use php\lib\str;

/**
 * Class FactoryFormat
 * @package ide\formats
 */
class FactoryFormat extends GuiFormFormat
{
    public function getIcon()
    {
        return 'icons/factory16.png';
    }

    public function createEditor($file, array $options = [])
    {
        return new FactoryEditor($file, new GuiFormDumper($this->formElementTags));
    }

    public function isValid($file)
    {
        if (str::endsWith($file, '.factory')) {
            return true;
        }

        return false;
    }
}