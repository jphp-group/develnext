<?php
namespace ide\webplatform;

use develnext\bundle\httpclient\HttpClientBundle;
use ide\AbstractExtension;
use ide\editors\value\ElementPropertyEditor;
use ide\webplatform\editors\value\CssClassPropertyEditor;
use ide\webplatform\editors\value\WebSizePropertyEditor;

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
        ElementPropertyEditor::register(new WebSizePropertyEditor());
    }

    public function onIdeStart()
    {

    }

    public function onIdeShutdown()
    {

    }
}