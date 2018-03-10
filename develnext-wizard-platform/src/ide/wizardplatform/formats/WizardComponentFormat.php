<?php
namespace ide\wizardplatform\formats;

use ide\editors\AbstractEditor;
use ide\formats\AbstractFormat;
use ide\wizardplatform\editors\WizardComponentEditor;

class WizardComponentFormat extends AbstractFormat
{
    /**
     * @param $file
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        return new WizardComponentEditor($file, $options);
    }

    /**
     * @param string $file
     * @return bool
     */
    public function isValid($file)
    {
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {
    }
}