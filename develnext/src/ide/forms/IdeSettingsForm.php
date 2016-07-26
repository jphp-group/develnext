<?php
namespace ide\forms;

use ide\settings\AbstractSettings;

class IdeSettingsForm extends AbstractIdeForm
{
    protected $default = null;

    /**
     * @param AbstractSettings $settings
     */
    public function setDefault(AbstractSettings $settings)
    {
        $this->default = $settings;
    }
}