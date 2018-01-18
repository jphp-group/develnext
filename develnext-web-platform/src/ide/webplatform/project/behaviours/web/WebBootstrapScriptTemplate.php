<?php
namespace ide\webplatform\project\behaviours\web;

use ide\misc\AbstractMetaTemplate;
use php\io\Stream;
use php\lib\str;

class WebBootstrapScriptTemplate extends AbstractMetaTemplate
{
    /**
     * @var string
     */
    private $mainUiClass = '';

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
        $mainUiClass = var_export($this->getMainUiClass(), true);

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
use php\\io\\Stream;

Stream::putContents('application.pid', getmypid());

\$deployer = new HotDeployer(function () {
    \$webUi = new WebUI();
 
    \$app = new WebApplication();
    \$app->addModule(\$webUi);
    \$webUi->addUI($mainUiClass);

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
\$app->addModule(\$webUi);
\$webUi->addUI($mainUiClass);

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
     * @return string
     */
    public function getMainUiClass(): string
    {
        return $this->mainUiClass;
    }

    /**
     * @param string $mainUiClass
     */
    public function setMainUiClass(string $mainUiClass)
    {
        $this->mainUiClass = $mainUiClass;
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