<?php
namespace ide;

use ide\editors\AbstractEditor;
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

    /**
     * @var string[]
     */
    protected $editors;

    public function launch()
    {
        parent::launch(function() {
            $this->splash = $splash = new SplashForm();
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
     * @param $editorClass
     */
    public function registerEditor($editorClass)
    {
        $this->editors[$editorClass] = $editorClass;
    }

    public function getEditor($path)
    {
        /** @var AbstractEditor $editor */
        foreach ($this->editors as $editorClass) {
            $editor = new $editorClass($path);

            if ($editor->isValid()) {
                return $editor;
            }
        }

        return null;
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