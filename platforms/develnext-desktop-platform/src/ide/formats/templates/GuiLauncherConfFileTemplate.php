<?php
namespace ide\formats\templates;

use ide\formats\AbstractFileTemplate;
use ide\project\AbstractProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;


class GuiLauncherConfFileTemplate extends AbstractFileTemplate
{
    private $fxSplash;
    private $fxSplashAlwaysOnTop = false;

    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            'FX_SPLASH' => $this->fxSplash,
            'FX_SPLASH_ALWAYS_ON_TOP' => $this->fxSplashAlwaysOnTop ? 1 : 0,
        ];
    }

    /**
     * @return mixed
     */
    public function getFxSplash()
    {
        return $this->fxSplash;
    }

    /**
     * @param mixed $fxSplash
     */
    public function setFxSplash($fxSplash)
    {
        $this->fxSplash = $fxSplash;
    }

    /**
     * @return boolean
     */
    public function isFxSplashAlwaysOnTop()
    {
        return $this->fxSplashAlwaysOnTop;
    }

    /**
     * @param boolean $fxSplashAlwaysOnTop
     */
    public function setFxSplashAlwaysOnTop($fxSplashAlwaysOnTop)
    {
        $this->fxSplashAlwaysOnTop = $fxSplashAlwaysOnTop;
    }
}