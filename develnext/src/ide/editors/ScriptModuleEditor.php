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
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractScript;
use php\gui\framework\DataUtils;
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
use php\io\File;
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

    public function __construct($file)
    {
        $this->manager = new ScriptComponentManager();
        parent::__construct($file, new GuiFormDumper([]));
    }

    public function save()
    {
        foreach ($this->manager->getComponents() as $el) {
            $this->manager->saveContainer($el);
        }
    }

    public function load()
    {
        $this->layout = new UXAnchorPane();
        $this->layout->minSize = [800, 600];
        $this->layout->size = [800, 600];
    }

    protected function _odnAreaMouseDown(UXMouseEvent $e)
    {
        $selected = $this->elementTypePane->getSelected();

        $this->save();

        /** @var AbstractScriptComponent $selected */
        if ($selected) {
            $node = $selected->createElement();

            $container = new ScriptComponentContainer($selected, $this->makeId($selected->getIdPattern()));
            $this->manager->add($container);


            $size = $node->size;

            $container->setX($e->x);
            $container->setY($e->y);

            $position = [$e->x, $e->y];

            $snapSize = $this->designer->snapSize;

            if ($this->designer->snapEnabled) {
                $size[0] = floor($size[0] / $snapSize) * $snapSize;
                $size[1] = floor($size[1] / $snapSize) * $snapSize;

                $position[0] = floor($position[0] / $snapSize) * $snapSize;
                $position[1] = floor($position[1] / $snapSize) * $snapSize;
            }

            $node->position = $position;

            $this->layout->add($node);
            $this->designer->registerNode($node);

            if (!$e->controlDown) {
                $this->elementTypePane->clearSelected();
            }

            foreach ($selected->getInitProperties() as $key => $property) {
                $container->{$key} = $property['value'];
            }

            $this->manager->add($container);
        } else {
            $this->updateProperties($this);
        }
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