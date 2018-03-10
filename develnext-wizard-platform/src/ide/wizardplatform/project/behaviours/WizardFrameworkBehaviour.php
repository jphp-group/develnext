<?php
namespace ide\wizardplatform\project\behaviours;

use ide\project\AbstractProjectBehaviour;

/**
 * Class WizardFrameworkBehaviour
 * @package ide\wizardplatform\project\behaviours
 */
class WizardFrameworkBehaviour extends AbstractProjectBehaviour
{
    /**
     * ...
     */
    public function inject()
    {
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_LIBRARY;
    }
}