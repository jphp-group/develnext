<?php
namespace ide;

use ide\editors\AbstractEditor;
use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ColorPropertyEditor;
use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\EnumPropertyEditor;
use ide\editors\value\FloatPropertyEditor;
use ide\editors\value\FontPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
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
use ide\formats\PhpCodeFormat;
use ide\forms\SplashForm;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use ide\project\templates\DefaultGuiProjectTemplate;
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

    /**
     * @var AbstractProjectTemplate[]
     */
    protected $projectTemplates = [];

    /**
     * @var Project
     */
    protected $openedProject = null;

    public function launch()
    {
        parent::launch(function() {
            $this->registerAll();

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
     * @param AbstractProjectTemplate $template
     */
    public function registerProjectTemplate(AbstractProjectTemplate $template)
    {
        $class = get_class($template);

        if (isset($this->projectTemplates[$class])) {
            return;
        }

        $this->projectTemplates[$class] = $template;
    }

    /**
     * @param AbstractFormat $format
     */
    public function registerFormat(AbstractFormat $format)
    {
        $class = get_class($format);

        if (isset($this->formats[$class])) {
            return;
        }

        $this->formats[$class] = $format;

        foreach ($format->getRequireFormats() as $el) {
            $this->registerFormat($el);
        }
    }

    /**
     * @param $class
     *
     * @return AbstractFormat
     */
    public function getRegisteredFormat($class)
    {
        return $this->formats[$class];
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
     * @return Project
     */
    public function getOpenedProject()
    {
        return $this->openedProject;
    }

    /**
     * @param Project $openedProject
     */
    public function setOpenedProject($openedProject)
    {
        $this->openedProject = $openedProject;
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
        ElementPropertyEditor::register(new SimpleTextPropertyEditor());
        ElementPropertyEditor::register(new TextPropertyEditor());
        ElementPropertyEditor::register(new IntegerPropertyEditor());
        ElementPropertyEditor::register(new FloatPropertyEditor());
        ElementPropertyEditor::register(new ColorPropertyEditor());
        ElementPropertyEditor::register(new FontPropertyEditor());
        ElementPropertyEditor::register(new EnumPropertyEditor([]));
        ElementPropertyEditor::register(new PositionPropertyEditor());
        ElementPropertyEditor::register(new BooleanPropertyEditor());

        $this->registerFormat(new PhpCodeFormat());
        $this->registerFormat(new GuiFormFormat());

        $this->registerProjectTemplate(new DefaultGuiProjectTemplate());
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