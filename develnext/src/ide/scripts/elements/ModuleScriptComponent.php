<?php
namespace ide\scripts\elements;

use ide\editors\ScriptModuleEditor;
use ide\scripts\AbstractScriptComponent;
use php\gui\framework\AbstractModule;

/**
 * Class ModuleScriptComponent
 * @package ide\scripts\elements
 */
class ModuleScriptComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof ScriptModuleEditor;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return AbstractModule::class;
    }

    public function getDescription()
    {

    }
}