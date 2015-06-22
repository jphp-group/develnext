<?php
namespace ide;

use ide\editors\AbstractEditor;
use ide\formats\AbstractFormat;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\ButtonFormElement;
use ide\formats\form\context\LockMenuCommand;
use ide\formats\form\context\ToBackMenuCommand;
use ide\formats\form\context\ToFrontMenuCommand;
use ide\formats\form\LabelFormElement;
use ide\formats\form\TextFieldFormElement;
use ide\formats\FormFormat;
use ide\formats\GuiFormFormat;
use ide\forms\SplashForm;
use php\gui\framework\Application;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXTab;
use php\io\File;
use php\io\Stream;

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
     * @var AbstractFormat[]
     */
    protected $formats = [];

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
     * @param AbstractFormat $format
     */
    public function registerFormat(AbstractFormat $format)
    {
        $this->formats[] = $format;
    }

    /**
     * @param string $path
     * @return UXImageView
     */
    public function getImage($path)
    {
        if ($path === null) {
            return null;
        }

        if ($path instanceof UXImage) {
            $image = $path;
        } elseif ($path instanceof UXImageView) {
            return $path;
        } elseif ($path instanceof Stream) {
            $image = new UXImage($path);
        } else {
            $image = new UXImage('res://.data/img/' . $path);
        }

        $result = new UXImageView();
        $result->image = $image;

        return $result;
    }

    /**
     * @param $path
     * @return AbstractFormat|null
     */
    public function getFormat($path)
    {
        /** @var AbstractFormat $format */
        foreach ($this->formats as $format) {
            if ($format->isValid($path)) {
                return $format;
            }
        }

        return null;
    }

    /**
     * @param $path
     * @return AbstractEditor
     */
    public function createEditor($path)
    {
        $format = $this->getFormat($path);

        if ($format) {
            $editor = $format->createEditor($path);
            $editor->setFormat($format);

            return $editor;
        }

        return null;
    }

    public function registerAll()
    {
        $this->registerFormat(new GuiFormFormat());
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