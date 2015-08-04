<?php
namespace ide\scripts\elements;

use ide\scripts\AbstractScriptComponent;
use script\TimerScript;

/**
 * Class TimerScriptComponent
 * @package ide\scripts
 */
class TimerScriptComponent extends AbstractScriptComponent
{
    /**
     * @return string
     */
    public function getType()
    {
        return TimerScript::class;
    }
}