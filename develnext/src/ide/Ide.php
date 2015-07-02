<?php
namespace ide;

use ide\commands\NewProjectCommand;
use ide\commands\OpenProjectCommand;
use ide\commands\SaveProjectCommand;
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
use ide\formats\form\ButtonFormElement;
use ide\formats\form\LabelFormElement;
use ide\formats\form\TextFieldFormElement;
use ide\formats\FormFormat;
use ide\formats\GuiFormFormat;
use ide\formats\PhpCodeFormat;
use ide\forms\MainForm;
use ide\forms\SplashForm;
use ide\misc\AbstractCommand;
use ide\project\AbstractProjectTemplate;
use ide\project\Project;
use ide\project\templates\DefaultGuiProjectTemplate;
use ide\systems\WatcherSystem;
use php\gui\framework\Application;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXMenu;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\System;
use php\lib\Str;
use php\util\Configuration;

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
     * @var AbstractCommand[]
     */
    protected $commands = [];

    /**
     * @var callable
     */
    protected $afterShow = [];

    /**
     * @var Configuration[]
     */
    protected $configurations = [];

    /**
     * @var Project
     */
    protected $openedProject = null;

    public function launch()
    {
        parent::launch(
            function () {
                $this->registerAll();

                $this->splash = $splash = new SplashForm();
                $splash->show();
            },
            function () {
                foreach ($this->afterShow as $handle) {
                    $handle();
                }
            }
        );
    }

    /**
     * @return SplashForm
     */
    public function getSplash()
    {
        return $this->splash;
    }

    public function setTitle($value)
    {
        $title = $this->getName() . ' ' . $this->getVersion();

        if ($value) {
            $title = $value . ' - ' . $title;
        }

        $this->getMainForm()->title = $title;
    }

    /**
     * @param string $name
     * @return Configuration
     */
    public function getUserConfig($name)
    {
        if ($config = $this->configurations[$name]) {
            return $config;
        }

        $config = new Configuration();

        try {
            $config->load(Stream::getContents($this->getFile("$name.conf")));
        } catch (IOException $e) {
            // ...
        }

        return $this->configurations[$name] = $config;
    }

    /**
     * @param string $key
     * @param mixed $def
     *
     * @return string
     */
    public function getUserConfigValue($key, $def = null)
    {
        return $this->getUserConfig('ide')->get($key, $def);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setUserConfigValue($key, $value)
    {
        $this->getUserConfig('ide')->set($key, $value);
    }

    /**
     * @param string $path
     *
     * @return File
     */
    public function getFile($path)
    {
        $home = System::getProperty('user.home');

        $ideHome = File::of("$home/.DevelNext");

        if (!$ideHome->isDirectory()) {
            $ideHome->mkdirs();
        }

        return File::of("$ideHome/$path");
    }

    /**
     * @return project\AbstractProjectTemplate[]
     */
    public function getProjectTemplates()
    {
        return $this->projectTemplates;
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

    public function unregisterCommands()
    {
        /** @var MainForm $mainForm */
        $mainForm = $this->getMainForm();

        if (!$mainForm) {
            return;
        }

        foreach ($this->commands as $data) {
            /** @var AbstractCommand $command */
            $command = $data['command'];

            if ($command->isAlways()) {
                continue;
            }

            if ($data['headUi']) {
                $mainForm->getHeadPane()->remove($data['headUi']);
            }

            if ($data['menuItem']) {
                /** @var UXMenu $menu */
                $menu = $mainForm->{'menu' . Str::upperFirst($command->getCategory())};

                if ($menu instanceof UXMenu) {
                    $menu->items->remove($data['menuItem']);
                }
            }
        }

        $this->commands = [];
    }

    /**
     * @param AbstractCommand $command
     */
    public function registerCommand(AbstractCommand $command)
    {
        $data = [
            'command' => $command,
        ];

        $headUi = $command->makeUiForHead();

        if ($headUi) {
            $data['headUi'] = $headUi;

            $this->afterShow(function () use ($headUi) {
                /** @var MainForm $mainForm */
                $mainForm = $this->getMainForm();

                if (!is_array($headUi)) {
                    $headUi = [$headUi];
                }

                foreach ($headUi as $ui) $mainForm->getHeadPane()->add($ui);
            });
        }

        $menuItem = $command->makeMenuItem();

        if ($menuItem) {
            $data['menuItem'] = $menuItem;

            $this->afterShow(function () use ($menuItem, $command) {
                /** @var MainForm $mainForm */
                $mainForm = $this->getMainForm();

                /** @var UXMenu $menu */
                $menu = $mainForm->{'menu' . Str::upperFirst($command->getCategory())};

                if ($menu instanceof UXMenu) {
                    $menu->items->add($menuItem);
                }
            });
        }

        $this->commands[get_class($command)] = $data;
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
     *
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
     *
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
        $this->setTitle($openedProject->getName() . " - [" . $openedProject->getRootDir() . "]");
    }

    /**
     * @param $path
     *
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

        $this->registerCommand(new NewProjectCommand());
        $this->registerCommand(new OpenProjectCommand());
        $this->registerCommand(new SaveProjectCommand());

        $ideConfig = $this->getUserConfig('ide');

        if (!$ideConfig->has('projectDirectory')) {
            $ideConfig->set('projectDirectory', File::of(System::getProperty('user.home') . '/DevelNextProjects/'));
        }
    }

    protected function afterShow(callable $handle)
    {
        $this->afterShow[] = $handle;
    }

    /**
     * @return Ide
     * @throws \Exception
     */
    public static function get()
    {
        return parent::get();
    }

    public function shutdown()
    {
        WatcherSystem::shutdown();

        $stream = null;

        foreach ($this->configurations as $name => $config) {
            try {
                $stream = Stream::of($this->getFile("$name.conf"), 'w+');
                $config->save($stream);
            } catch (IOException $e) {
                throw $e;
            } finally {
                if ($stream) $stream->close();
            }
        }
    }
}