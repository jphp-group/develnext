<?php
namespace ide;

use ide\systems\DialogSystem;

class IdeStandardExtension extends AbstractExtension
{
    public function onRegister()
    {
        DialogSystem::registerDefaults();
    }

    public function onIdeStart()
    {
    }

    public function onIdeShutdown()
    {
    }
}