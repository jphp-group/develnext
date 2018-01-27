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
            $watchedCode[$watchingDir] = "\$deployer->addDirWatcher($string)";
        }
        $watchedCode = str::join($watchedCode, "\r\n");

        if ($this->hotDeployEnabled) {
            $out->write("<?php

use framework\\core\\Event;
use framework\\web\\HotDeployer;
use framework\\web\\WebApplication;
use framework\\web\\WebUI;
use framework\\web\\WebAssets;
use php\\io\\Stream;

Stream::putContents('application.pid', getmypid());

\$deployer = new HotDeployer(function () {
    \$webUi = new WebUI();
 
    \$app = new WebApplication();
    \$app->addModule(new WebAssets('/assets', './assets'));
    \$app->addModule(\$webUi);
    
$mainUiClassCode

    \$app->launch();
}, function () {
    \$app = WebApplication::current();
    \$app->trigger(new Event('restart', \$app));
    \$app->shutdown();
});

$watchedCode
\$deployer->run();
            ");
        } else {
            $out->write("<?php

use framework\\core\\Event;
use framework\\web\\WebApplication;
use framework\\web\\WebUI;
use php\\io\\Stream;

Stream::putContents('application.pid', getmypid());

\$webUi = new WebUI();

\$app = new WebApplication();
\$app->addModule(new WebAssets('/assets', './assets'));
\$app->addModule(\$webUi);

$mainUiClassCode

\$app->launch();
            ");
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