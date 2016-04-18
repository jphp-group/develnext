<?php
namespace ide\project\control;
use ide\editors\CodeEditor;
use ide\Ide;
use ide\utils\FileUtils;
use ide\utils\StrUtils;
use php\gui\UXApplication;
use php\gui\UXNode;
use php\gui\layout\UXAnchorPane;
use php\lib\str;
use php\util\Regex;

/**
 * @package ide\project\control
 */
class DesignProjectControlPane extends AbstractProjectControlPane
{
    /**
     * @var CodeEditor
     */
    protected $editor;

    /**
     * @var
     */
    protected $ideStylesheet;

    /**
     * @var bool
     */
    protected $loaded = false;


    public function getName()
    {
        return "Внешний вид";
    }

    public function getDescription()
    {
        return "CSS стиль и дизайн проекта";
    }

    public function getIcon()
    {
        return 'icons/design16.png';
    }

    public function save()
    {
        if ($this->editor) {
            $this->editor->save();
            $this->reloadStylesheet();
        }
    }

    public function open()
    {
        $this->reloadStylesheet();
    }


    public function load()
    {
        $this->ideStylesheet = Ide::project()->getSrcFile('.theme/style-ide.css');

        if ($this->editor) {
            $this->editor->load();
            $this->loaded = true;
        }
    }

    public function reloadStylesheet()
    {
        if (!UXApplication::isUiThread()) {
            uiLater(function () {
               $this->reloadStylesheet();
            });
            return;
        }

        if ($form = Ide::get()->getMainForm()) {
            $source = FileUtils::get(Ide::project()->getSrcFile('.theme/style.css'));

            $regex = Regex::of('((\.|\#)[\w\d\-\_\:\# ]{1,}\{)')->with($source)->withFlags(Regex::MULTILINE | Regex::DOTALL);

            $source = $regex->replaceWithCallback(function () {
                return '.FormEditor $1';
            });

            FileUtils::put($this->ideStylesheet, $source);

            $path = "file:///" . str::replace($this->ideStylesheet, "\\", "/");

            $form->removeStylesheet($path);
            $form->addStylesheet($path);
        }
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        $file = Ide::project()->getSrcFile('.theme/style.css');

        if (!$file->exists()) {
            FileUtils::put($file, "/* JavaFX CSS Style with -fx- prefix */\n");
        }

        $editor = new CodeEditor($file, 'css');
        $editor->registerDefaultCommands();

        $editor->load();

        $this->editor = $editor;

        $this->reloadStylesheet();

        return $editor->makeUi();
    }

    /**
     * Refresh ui and pane.
     */
    public function refresh()
    {
        if ($this->ui) {
            $this->ui->requestFocus();

            uiLater(function () {
                $this->editor->requestFocus();
            });
        }
        // nop.
    }
}