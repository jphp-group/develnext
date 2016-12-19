<?php
namespace ide\jsplatform;

use bundle\http\HttpDownloader;
use ide\Ide;
use ide\systems\IdeSystem;
use php\gui\framework\AbstractForm;

/**
 * Class DownloadingJsToolForm
 * @package ide\jsplatform
 */
class DownloadingJsToolForm extends AbstractForm
{
    /**
     * @var HttpDownloader
     */
    protected $downloader;

    /**
     * ...
     */
    protected function init()
    {
        parent::init();

        $downloader = new HttpDownloader();
        $downloader->destDirectory = IdeSystem::getFile('');

        $downloader->urls = [
            'https://nodejs.org/dist/v6.9.2/node-v6.9.2-win-x86.zip'
        ];

        $downloader->start();
    }

    /**
     * @return string
     */
    protected function getResourcePath()
    {
        return static::getResourcePathByClassName();
    }
}