<?php
namespace ide\webplatform;

use develnext\bundle\httpclient\HttpClientBundle;
use ide\AbstractExtension;
use ide\editors\value\ElementPropertyEditor;
use ide\webplatform\editors\value\CssClassPropertyEditor;

class WebPlatformExtension extends AbstractExtension
{
    public function getDependencies()
    {
        return [
            HttpClientBundle::class
        ];
    }

    public function onRegister()
    {
        ElementPropertyEditor::register(new CssClassPropertyEditor());
    }

    public function onIdeStart()
    {

    }

    public function onIdeShutdown()
    {

    }
}