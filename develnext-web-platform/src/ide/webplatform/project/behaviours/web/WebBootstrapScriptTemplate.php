<?php

namespace ide\webplatform\project\behaviours\web;

use ide\misc\AbstractMetaTemplate;
use php\io\Stream;
use php\lib\arr;
use php\lib\str;

class WebBootstrapScriptTemplate extends AbstractMetaTemplate
{
    /**
     * @var array
     */
    private $uiClasses = [];

    /**
     * @var array
     */
    private $watchingDirs = [];

    /**
     * @var array
     */
    private $watchingFiles = [];

    /**
     * @var array
     */
    private $sourceDirs = [];

    /**
     * @var bool
     */
    private $hotDeployEnabled = false;

    /**
     * @param Stream $out
     */
    public function render(Stream $out)
    {
        $mainUiClassCode = [];
        foreach ($this->getUiClasses() as $class) {
            $class = var_export($class, true);
            $mainUiClassCode[] = "\t\$webUi->addUI($class);";
        }
        $mainUiClassCode = str::join($mainUiClassCode, "\r\n");

        $watchedCode = [];
        foreach ($this->getWatchingDirs() as $watchingDir) {
            $string = var_export($watchingDir, true);
            $watchedCode[] = "\$deployer->addDirWatcher($string)";
        }

        foreach ($this->getWatchingFiles() as $watchingFile) {
            $string = var_export($watchingFile, true);
            $watchedCode[] = "\$deployer->addFileWatcher($string)";
        }

        foreach ($this->getSourceDirs() as $sourceDir) {
            $string = var_export($sourceDir, true);
            $watchedCode[] = "\$deployer->addSourceDir($string)";
        }

        $watchedCode = str::join($watchedCode, "\r\n");

        $out->write("<?php

use framework\\core\\Event;
use framework\\web\\HotDeployer;
use framework\\web\\WebApplication;
use framework\\web\\WebAssets;
use framework\\web\\WebDevModule;
use framework\\app\\web\\WebServerAppModule;
use php\\io\\Stream;

Stream::putContents('application.pid', getmypid());

\$deployer = new HotDeployer(function () {
    \$webUi = new WebServerAppModule();
 
    \$app = new WebApplication();
    \$app->addModule(new WebDevModule());
    \$app->addModule(new WebAssets('/assets', './assets'));
    \$app->addModule(\$webUi);
    
$mainUiClassCode

    \$app->launch();
}, function () {
    if (WebApplication::isInitialized()) {
        \$app = WebApplication::current();
        \$app->trigger(new Event('restart', \$app));
        \$app->shutdown();
    }
});

$watchedCode
            ");

        if ($this->hotDeployEnabled) {
            $out->write("\$deployer->run();");
        } else {
            $out->write("\$deployer->directRun();");
        }
    }

    /**
     * @return array
     */
    public function getWatchingDirs(): array
    {
        return $this->watchingDirs;
    }

    /**
     * @param array $watchingDirs
     */
    public function setWatchingDirs(array $watchingDirs)
    {
        $this->watchingDirs = $watchingDirs;
    }

    /**
     * @return array
     */
    public function getWatchingFiles(): array
    {
        return $this->watchingFiles;
    }

    /**
     * @param array $watchingFiles
     */
    public function setWatchingFiles(array $watchingFiles)
    {
        $this->watchingFiles = $watchingFiles;
    }

    /**
     * @return array
     */
    public function getUiClasses(): array
    {
        return $this->uiClasses;
    }

    /**
     * @param array $uiClasses
     */
    public function setUiClasses(array $uiClasses)
    {
        $this->uiClasses = arr::combine($uiClasses, $uiClasses);
    }

    /**
     * @param string $uiClass
     */
    public function addUiClass(string $uiClass)
    {
        $this->uiClasses[$uiClass] = $uiClass;
    }

    /**
     * @param string $uiClass
     */
    public function removeUiClass(string $uiClass)
    {
        unset($this->uiClasses[$uiClass]);
    }

    /**
     * @return array
     */
    public function getSourceDirs(): array
    {
        return $this->sourceDirs;
    }

    /**
     * @param array $sourceDirs
     */
    public function setSourceDirs(array $sourceDirs)
    {
        $this->sourceDirs = $sourceDirs;
    }

    /**
     * @return bool
     */
    public function isHotDeployEnabled(): bool
    {
        return $this->hotDeployEnabled;
    }

    /**
     * @param bool $hotDeployEnabled
     */
    public function setHotDeployEnabled(bool $hotDeployEnabled)
    {
        $this->hotDeployEnabled = $hotDeployEnabled;
    }
}