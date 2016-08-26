<?php
namespace develnext\bundle\httpclient\components;

use bundle\http\HttpChecker;
use bundle\http\HttpClient;
use bundle\http\HttpDownloader;
use ide\scripts\AbstractScriptComponent;

class HttpCheckerComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof HttpCheckerComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'HTTP Монитор';
    }

    public function getIcon()
    {
        return 'develnext/bundle/httpclient/pulse16.png';
    }

    public function getIdPattern()
    {
        return "httpChecker%s";
    }

    public function getGroup()
    {
        return 'Интернет и сеть';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return HttpChecker::class;
    }

    public function getDescription()
    {
        return null;
    }
}