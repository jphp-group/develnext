<?php
namespace ide\project\control;
use ide\editors\CodeEditor;
use ide\editors\CodeEditorX;
use ide\Ide;
use ide\utils\FileUtils;
use ide\utils\StrUtils;
use php\gui\designer\UXCssCodeArea;
use php\gui\designer\UXSyntaxTextArea;
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
     * @var CodeEditor|CodeEditorX
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
        return "CSS стиль и дизайн";
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

        if ($this->editor) {
            $this->editor->refreshUi();
        }
    }

    public function load()
    {
        $this->ideStylesheet = Ide::project()->getIdeCacheFile('.theme/style-ide.css');

        if ($this->editor) {
            /*$file = $this->editor->data('file');
            $this->editor->text = FileUtils::get($file);  */
            $this->editor->load();

            $this->loaded = true;
        }
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        $editor = new CodeEditor($file, 'fxcss');
        $editor->registerDefaultCommands();

        $editor->loadContentToArea();
        $this->editor = $editor;

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