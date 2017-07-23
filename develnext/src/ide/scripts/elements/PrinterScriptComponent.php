<?php
namespace ide\scripts\elements;

use ide\library\IdeLibraryScriptGeneratorResource;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use script\PrinterScript;

class PrinterScriptComponent extends AbstractScriptComponent
{
    public function getGroup()
    {
        return 'Системное';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return PrinterScript::class;
    }

    public function getDescription()
    {
        return null;
    }

    public function getPlaceholder(ScriptComponentContainer $container)
    {
        return '';
    }

    public function getIdPattern()
    {
        return 'printer%s';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Принтер';
    }

    public function getIcon()
    {
        return 'icons/printer16.png';
    }

    public function isOrigin($any)
    {
        return $any instanceof PrinterScriptComponent;
    }

    public function getScriptGenerators()
    {
        return [
            new IdeLibraryScriptGeneratorResource('res://.dn/bundle/uiDesktop/scriptgen/module/PrintNodeSimpleScriptGen'),
        ];
    }


}