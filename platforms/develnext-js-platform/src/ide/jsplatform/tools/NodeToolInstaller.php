<?php
namespace ide\jsplatform\tools;

use bundle\http\HttpDownloader;
use ide\Ide;
use ide\Logger;
use ide\systems\IdeSystem;
use ide\tool\AbstractTool;
use ide\tool\AbstractToolInstaller;
use php\compress\ZipFile;
use php\gui\framework\ScriptEvent;
use php\lang\System;
use php\lang\Thread;
use php\lib\fs;
use php\lib\str;

/**
 * Class NodeToolInstaller
 * @package ide\jsplatform\tools
 */
class NodeToolInstaller extends AbstractToolInstaller
{
    /**
     * @return mixed
     */
    public function run()
    {
        $this->triggerProgress('start', 0);

        Logger::info("Install node....");

        $this->triggerProgress('initialize', 0);

        $downloader = new HttpDownloader();
        $downloader->destDirectory = IdeSystem::getFile('');

        $version = $this->tool->getVersion();

        if (Ide::get()->isWindows()) {
            $os = 'win';
            $arch = (bool) (System::getEnv()['ProgramFiles(x86)']) ? 'x64' : 'x86';
        } else if (Ide::get()->isMac()) {
            $os = 'darwin';
            $arch = 'x64';
        } else {
            $os = 'linux';
            $arch = str::contains(System::getProperty('os.arch'), '64') ? 'x64' : 'x86';
        }

        $downloader->urls = [
            "https://nodejs.org/dist/v$version/node-v$version-$os-$arch.zip"
        ];

        $this->triggerMessage('Downloading ' . $downloader->urls[0]);

        $downloader->start();
        $downloader->on('progress', function (ScriptEvent $e) {
            $this->triggerProgress('download', $e->progress / $e->max * 100);
        });

        $downloader->on('errorOne', function () use ($downloader) {
            $this->triggerMessage('Unable to download ' . $downloader->urls[0]);
            $this->triggerDone(false);
        });

        $downloader->on('successAll', function () use ($version, $os, $arch) {
            $this->triggerMessage('Done.');

            $th = new Thread(function () use ($version, $os, $arch) {
                try {
                    $this->triggerMessage('Unpack archive ' . "node-v$version-$os-$arch.zip");

                    $zipFile = new ZipFile(IdeSystem::getFile("node-v$version-$os-$arch.zip"));
                    $this->triggerProgress('unpack', 0);

                    $size = sizeof($zipFile->getEntryNames());

                    foreach ($zipFile->getEntryNames() as $i => $name) {
                        $this->triggerProgress('unpack', $i / $size * 100);

                        $entry = $zipFile->getEntry($name);

                        $path = $name;
                        $path = str::sub($path, str::pos($path, '/'));

                        $path = $this->tool->getBinPath() . '/' . $path;

                        if ($entry->isDirectory()) {
                            fs::makeDir($path);
                        } else {
                            fs::ensureParent($path);
                            fs::copy($zipFile->getEntryStream($name), $path);
                        }
                    }

                    $this->triggerMessage('Done.');
                    $this->triggerProgress('unpack', 100);

                    $zipFile->close();

                    $this->triggerDone(true);
                } catch (\Exception $e) {
                    $this->triggerMessage('Error to unpack tool zip', 'error');
                    $this->triggerMessage($e->getMessage(), 'error');
                    $this->triggerMessage($e->getTraceAsString(), 'error');
                    $this->triggerDone(false);
                }
            });
            $th->start();
        });
    }
}