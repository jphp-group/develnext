<?php
namespace ide\editors;

use Files;
use ide\editors\form\FormElementTypePane;
use ide\editors\menu\ContextMenu;
use ide\formats\form\AbstractFormDumper;
use ide\formats\GuiFormDumper;
use ide\formats\ScriptModuleFormat;
use ide\forms\MainForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\ScriptComponentContainer;
use ide\scripts\ScriptComponentManager;
use php\gui\designer\UXDesignProperties;
use php\gui\framework\AbstractScript;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCell;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\lib\Items;
use php\lib\Str;

/**
 * Class ScriptModuleEditor
 * @package ide\editors
 *
 * @property ScriptModuleFormat $format
 */
class ScriptModuleEditor extends FormEditor
{
    /** @var ScriptComponentManager */
    protected $manager;

    /**
     * @var UXListView
     */
    protected $scriptList;

    public function __construct($file)
    {
        parent::__construct($file, new GuiFormDumper([]));
    }

    public function save()
    {
        ;
    }

    public function load()
    {
        $this->layout = new UXAnchorPane();
        $this->layout->minSize = [500, 500];
    }

    public function updateScriptList()
    {
        $this->scriptList->items->clear();
        $this->scriptList->items->addAll($this->manager->getComponents());
    }

    public function makeId($idPattern)
    {
        $id = Str::format($idPattern, '');

        if (Files::exists($this->file . "/" . $id . '.json')) {
            $id = Str::format($idPattern, 'Alt');

            if (Files::exists($this->file . "/$id.json")) {
                $n = 3;

                do {
                    $id = Str::format($idPattern, $n++);
                } while (Files::exists($this->file . "/$id.json"));
            }
        }

        return $id;
    }
}