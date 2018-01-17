<?php
namespace ide\webplatform\formats;

use framework\web\UIForm;
use ide\editors\AbstractEditor;
use ide\formats\AbstractFormFormat;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\context\LockMenuCommand;
use ide\formats\form\context\RelocationMenuCommand;
use ide\formats\form\context\SelectAllMenuCommand;
use ide\formats\form\context\ToBackMenuCommand;
use ide\formats\form\context\ToFrontMenuCommand;
use ide\formats\templates\PhpClassFileTemplate;
use ide\forms\InputMessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\project\Project;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use ide\utils\Json;
use ide\webplatform\editors\WebFormEditor;
use php\gui\UXNode;
use php\io\File;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

/**
 * Class WebFormFormat
 * @package ide\webplatform\formats
 */
class WebFormFormat extends AbstractFormFormat
{
    /**
     * @var WebFormDumper
     */
    protected $dumper;

    /**
     * WebFormFormat constructor.
     */
    public function __construct()
    {
        // Context Menu.
        $this->register(new SelectAllMenuCommand());
        //$this->register(new CutMenuCommand());
        //$this->register(new CopyMenuCommand());
        //$this->register(new PasteMenuCommand());
        $this->register(new DeleteMenuCommand());
        $this->register(new ToFrontMenuCommand());
        $this->register(new ToBackMenuCommand());
        $this->register(new LockMenuCommand());

        $this->registerRelocationCommands();

        $this->dumper = new WebFormDumper($this->formElementTags);
    }

    public function getIcon()
    {
        return 'icons/window16.png';
    }

    public function getTitle($path)
    {
        return fs::nameNoExt(parent::getTitle($path));
    }

    /**
     * @param $file
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        return new WebFormEditor($file, new WebFormDumper($this->formElementTags));
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        $ext = fs::ext($file);

        if ($ext != 'php') {
            return false;
        }

        if (!fs::isFile("$file.frm")) {
            return false;
        }

        return true;
    }

    public function availableCreateDialog()
    {
        return true;
    }

    public function showCreateDialog($name = '')
    {
        $dialog = new InputMessageBoxForm('Создание новой формы', 'Введите название для новой формы', '* Только латинские буквы, цифры и _');
        $dialog->setResult($name);
        $dialog->setPattern(new Regex('^[a-z\\_]{1}[a-z0-9\\_]{0,60}$', 'i'), 'Данное название некорректное');

        $dialog->showDialog();
        return $dialog->getResult();
    }

    public function createBlank(Project $project, $name, array $options)
    {
        Logger::info("Creating form '$name' ...");

        $namespace = $options['namespace'] ?? "{$project->getPackageName()}\\forms";
        $file = $project->getSrcFile(str::replace($namespace, '\\', '/') . "/$name");

        $template = new PhpClassFileTemplate($name, 'UIForm');
        $template->setNamespace($namespace);
        $template->setImports([UIForm::class]);

        $sources = $project->createFile($project->getAbsoluteFile("$file.php"), $template);
        $sources->applyTemplate($template);
        $sources->updateTemplate(true);

        $frm = [
            'title' => $name,
            'layout' => ["_" => "AnchorPane"]
        ];

        Json::toFile("$file.php.frm", $frm);

        return $file;
    }

    public function createBlankCommand()
    {
        return AbstractCommand::makeWithText('Новая форма', $this->getIcon(), function () {
            $project = Ide::project();

            if ($project) {
                $name = $this->showCreateDialog();
                if ($name !== null) {
                    $file = $this->createBlank($project, $name, []);
                    FileSystem::open($file);
                }
            }
        });
    }

    public function delete($path, $silent = false)
    {
        parent::delete($path);

        $name = fs::nameNoExt($path);

        /*if (!$silent) {
            if ($behaviour = GuiFrameworkProjectBehaviour::get()) {
                if ($behaviour->getMainForm() == $name) {
                    $dialog = new SetMainFormForm();
                    $dialog->setExcludedForms([$name]);
                    $dialog->showDialog();

                    $behaviour->setMainForm($dialog->getResult(), false);
                }
            }
        }*/

        $parent = fs::parent($path);

        fs::delete("$parent/$name.php");
        fs::delete("$parent/$name.php.frm");
        fs::delete("$parent/$name.php.sourcemap");
        fs::delete("$parent/$name.php.axml");
        fs::delete("$parent/$name.behaviour");
    }

    public function duplicate($path, $toPath)
    {
        parent::duplicate($path, $toPath);

        $name = fs::nameNoExt($path);
        $toName = fs::nameNoExt($toPath);

        $parent = File::of($path)->getParent();
        $toParent = File::of($toPath)->getParent();

        $path = $parent;// . '/../app/forms/';
        $toPath = $toParent;// . '/../app/forms/';

        foreach (['php', 'php.frm', 'php.axml', 'behaviour'] as $ext) {
            if (fs::isFile("$path/$name.$ext")) {
                FileUtils::copyFile("$path/$name.$ext", "$toPath/$toName.$ext");

                if ($ext == 'php') {
                    FileUtils::replaceInFile("$toPath/$toName.$ext", "class $name extends", "class $toName extends");
                }
            }
        }
    }

    public function register($any)
    {
        parent::register($any);

        if ($this->dumper) {
            $this->dumper->setFormElementTags($this->formElementTags);
        }
    }

    protected function registerRelocationCommands()
    {
        $this->register(new RelocationMenuCommand('Up', function (UXNode $node, $sizeX, $sizeY) {
            $node->y -= $sizeY;
        }));

        $this->register(new RelocationMenuCommand('Down', function (UXNode $node, $sizeX, $sizeY) {
            $node->y += $sizeY;
        }));

        $this->register(new RelocationMenuCommand('Left', function (UXNode $node, $sizeX) {
            $node->x -= $sizeX;
        }));

        $this->register(new RelocationMenuCommand('Right', function (UXNode $node, $sizeX) {
            $node->x += $sizeX;
        }));
    }
}