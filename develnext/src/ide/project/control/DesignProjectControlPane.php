<?php
namespace ide\project\control;
use ide\editors\CodeEditor;
use ide\editors\CodeEditorX;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
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
     * @var CodeEditor
     */
    protected $editor;

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
        }
    }

    public function open()
    {
        if ($this->editor) {
            $this->editor->refreshUi();
            $this->editor->loadContentToArea(false);
        }
    }

    public function load()
    {
        if ($this->editor) {
            $this->editor->load();

            $this->loaded = true;
        }
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        $path = Ide::project()->getSrcFile('.theme/style.fx.css');
        $this->editor = Ide::get()->getFormat($path)->createEditor($path);
        $this->editor->setTabbed(false);
        $this->editor->loadContentToArea();

        return $this->editor->makeUi();
    }

    /**
     * Refresh ui and pane.
     */
    public function refresh()
    {
        if ($this->ui) {
            $this->editor->loadContentToAreaIfModified();

            $this->ui->requestFocus();

            uiLater(function () {
                $this->editor->requestFocus();
            });
        }
        // nop.
    }
}