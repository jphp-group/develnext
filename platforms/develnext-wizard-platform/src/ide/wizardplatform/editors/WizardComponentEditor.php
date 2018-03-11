<?php
namespace ide\wizardplatform\editors;

use ide\editors\CodeEditor;

class WizardComponentEditor extends CodeEditor
{
    /**
     * WizardComponentEditor constructor.
     * @param string $file
     * @param array $options
     */
    public function __construct($file, array $options = array())
    {
        parent::__construct($file, 'php', $options);
    }
}