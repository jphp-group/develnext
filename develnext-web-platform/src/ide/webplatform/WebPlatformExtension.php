<?php
namespace ide\webplatform;

use develnext\bundle\httpclient\HttpClientBundle;
use ide\AbstractExtension;

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

    }

    public function onIdeStart()
    {

    }

    public function onIdeShutdown()
    {

    }
}