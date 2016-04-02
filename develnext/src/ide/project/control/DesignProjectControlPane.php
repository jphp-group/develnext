<?php
namespace ide\project\control;
use ide\editors\CodeEditor;
use ide\Ide;
use ide\utils\FileUtils;
use php\gui\UXNode;
use php\gui\layout\UXAnchorPane;

/**
 * @package ide\project\control
 */
class DesignProjectControlPane extends AbstractProjectControlPane
{
    /**
     * @var CodeEditor
     */
    protected $editor;

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
        }
    }

    public function load()
    {
        if ($this->editor) {
            $this->editor->load();
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