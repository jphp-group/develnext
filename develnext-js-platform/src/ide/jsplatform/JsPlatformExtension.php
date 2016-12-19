<?php
namespace ide\jsplatform;

use bundle\http\HttpDownloader;
use develnext\bundle\httpclient\HttpClientBundle;
use ide\AbstractExtension;
use ide\forms\ToolInstallForm;
use ide\Ide;
use ide\jsplatform\tools\ElectronTool;
use ide\jsplatform\tools\GulpTool;
use ide\jsplatform\tools\NodeTool;
use ide\jsplatform\tools\NpmTool;
use ide\Logger;
use ide\systems\IdeSystem;
use php\compress\ZipFile;
use php\lang\Thread;
use php\lib\fs;
use php\lib\str;
use php\util\Scanner;

/**
 * Class JsPlatformExtension
 * @package ide\jsplatform
 */
class JsPlatformExtension extends AbstractExtension
{
    public function onRegister()
    {
        IdeSystem::getLoader()->addClassPath((new HttpClientBundle())->getVendorDirectory());

        $toolManager = Ide::get()->getToolManager();

        $toolManager->register(new NodeTool('6.9.2'));
        $toolManager->register(new NpmTool());
        $toolManager->register(new GulpTool('3.9.1'));
        $toolManager->register(new ElectronTool());
    }

    public function onIdeShutdown()
    {

    }

    public function onIdeStart()
    {
        $toolManager = Ide::get()->getToolManager();

        /*$toolManager->install(['node', 'npm', 'gulp', 'electron'], function ($success) use ($toolManager) {

        });*/

        /*$install->on('progress', function ($status, $progress) {
            var_dump($status, $progress);
        });*/

        /*$install->on('done', function () use ($toolManager) {
            $toolManager->install('gulp')->on('message', function ($message, $type) {
                if ($type == 'info') {
                    Logger::info($message);
                }

                if ($type == 'error') {
                    Logger::error($message);
                }
            });
        });*/

        /*if (!$toolManager->get('node')->isAvailable()) {
            $installer = $toolManager->get('node')->setup();
            $installer->run();
            $installer->on('progress', function ($status, $progress) {
                Logger::warn("$status - $progress");
            });
        }*/
    }
}