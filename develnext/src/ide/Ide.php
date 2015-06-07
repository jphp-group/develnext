<?php
namespace ide;

use ide\forms\SplashForm;
use php\gui\framework\Application;
use php\gui\UXForm;

/**
 * Class Ide
 * @package ide
 */
class Ide extends Application
{
    /**
     * @var SplashForm
     */
    protected $splash;

    public function launch()
    {
        parent::launch(function() {
            $this->splash = $splash = new SplashForm(new UXForm('UNDECORATED'));
            $splash->show();
        });
    }

    /**
     * @return SplashForm
     */
    public function getSplash()
    {
        return $this->splash;
    }

    /**
     * @return Ide
     * @throws \Exception
     */
    public static function get()
    {
        return parent::get();
    }
}