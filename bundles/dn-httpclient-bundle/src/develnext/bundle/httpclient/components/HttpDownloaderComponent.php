<?php
namespace develnext\bundle\httpclient\components;

use bundle\http\HttpClient;
use bundle\http\HttpDownloader;
use ide\scripts\AbstractScriptComponent;
use script\MailScript;

class HttpDownloaderComponent extends AbstractScriptComponent
{
    public function isOrigin($any)
    {
        return $any instanceof HttpDownloaderComponent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Загрузчик файлов';
    }

    public function getIcon()
    {
        return 'develnext/bundle/httpclient/httpDownloader16.png';
    }

    public function getIdPattern()
    {
        return "downloader%s";
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
        return HttpDownloader::class;
    }

    public function getDescription()
    {
        return null;
    }
}