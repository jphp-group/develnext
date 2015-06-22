<?php
namespace ide\editors;

use ide\editors\form\FormElementTypePane;
use ide\editors\menu\ContextMenu;
use ide\formats\AbstractFormFormat;
use ide\formats\form\AbstractFormDumper;
use ide\formats\FormFormat;
use ide\forms\MainForm;
use ide\Ide;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDesignPane;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXSplitPane;
use php\gui\UXTooltip;
use php\io\File;
use php\lib\Items;
use php\lib\String;
use php\util\Configuration;

/**
 * Class FormEditor
 * @package ide\editors
 *
 * @property AbstractFormFormat $format
 */
class FormEditor extends AbstractEditor
{
    const BORDER_SIZE = 8;

    /**
     * @var UXPane
     */
    protected $layout;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var UXDesigner
     */
    protected $designer;

    /**
     * @var FormElementTypePane
     */
    protected $elementTypePane;

    /**
     * @var ContextMenu
     */
    protected $contextMenu;

    /**
     * @var AbstractFormDumper
     */
    protected $formDumper;

    /**
     * @var UXDesignProperties[]
     */
    protected $typeProperties = [];

    public function __construct($file, AbstractFormDumper $dumper)
    {
        parent::__construct($file);

        $this->formDumper = $dumper;
    }

    public function getTitle()
    {
        return File::of($this->file)->getName();
    }

    public function getIcon()
    {
        return 'icons/window16.png';
    }

    public function getTooltip()
    {
        $tooltip = new UXTooltip();
        $tooltip->text = (new File($this->file))->getPath();

        return $tooltip;
    }

    /**
     * @param UXPane $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return UXPane
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return AbstractFormDumper
     */
    public function getFormDumper()
    {
        return $this->formDumper;
    }

    /**
     * @return UXDesigner
     */
    public function getDesigner()
    {
        return $this->designer;
    }

    /**
     * @return UXNode
     */
    public function load()
    {
        $this->formDumper->load($this);
    }

    public function save()
    {
        $this->formDumper->save($this);
    }

    public function makeUi()
    {
        if (!$this->layout) {
            throw new \Exception("Cannot open unloaded form");
        }

        $area = new UXAnchorPane();

        $viewer = new UXScrollPane($area);

        $designPane = new UXDesignPane();
        $designPane->size = $this->layout->size;
        $designPane->position = [10, 10];
        $designPane->add($this->layout);

        UXAnchorPane::setTopAnchor($this->layout, 0);
        UXAnchorPane::setLeftAnchor($this->layout, 0);
        UXAnchorPane::setBottomAnchor($this->layout, 0);
        UXAnchorPane::setRightAnchor($this->layout, 0);

        $this->designer = new UXDesigner($this->layout);
        $this->designer->onAreaMouseDown([$this, '_onAreaMouseDown']);
        $this->designer->onNodeClick([$this, '_onNodeClick']);
        $this->designer->onNodePick([$this, '_onNodePick']);
        $this->designer->onChanged([$this, '_onChanged']);

        foreach ($this->layout->children as $node) {
            $this->designer->registerNode($node);
        }

        $area->add($designPane);

        $elementPane = new UXScrollPane();
        $elementPane->maxWidth = 250;

        $this->elementTypePane = new FormElementTypePane($this->format->getFormElements());

        $split = new UXSplitPane([$viewer, $this->elementTypePane->getContent()]);

        $this->makeContextMenu();

        return $split;
    }

    protected function makeContextMenu()
    {
        $this->contextMenu = new ContextMenu($this, $this->format->getContextCommands());
        $this->designer->contextMenu = $this->contextMenu->getRoot();
    }

    protected function _onAreaMouseDown(UXMouseEvent $e)
    {
        $selected = $this->elementTypePane->getSelected();

        $this->save();

        if ($selected) {
            $node = $selected->createElement();

            $size = $selected->getDefaultSize();
            $position = [$e->x, $e->y];

            $snapSize = $this->designer->snapSize;

            if ($this->designer->snapEnabled) {
                $size[0] = floor($size[0] / $snapSize) * $snapSize;
                $size[1] = floor($size[1] / $snapSize) * $snapSize;

                $position[0] = floor($position[0] / $snapSize) * $snapSize;
                $position[1] = floor($position[1] / $snapSize) * $snapSize;
            }

            $node->size = $size;
            $node->position = $position;

            $this->layout->add($node);
            $this->designer->registerNode($node);

            if (!$e->controlDown) {
                $this->elementTypePane->clearSelected();
            }
        }
    }

    protected function _onChanged()
    {
        $this->_onNodePick();
    }

    protected function _onNodePick()
    {
        $node = $this->designer->pickedNode;

        if ($node) {
            $this->updateProperties($node);
        }
    }

    protected function _onNodeClick(UXMouseEvent $e)
    {
        $selected = $this->elementTypePane->getSelected();

        if ($selected) {
            $this->designer->unselectAll();
            $this->elementTypePane->clearSelected();
            return true;
        }
    }

    protected function updateProperties(UXNode $node)
    {
        $element = $this->format->getFormElement($node);

        /** @var MainForm $mainForm */
        $mainForm = Ide::get()->getMainForm();
        $pane = $mainForm->getPropertiesPane();

        $properties = $this->typeProperties[get_class($node)];

        if (!$properties) {
            $properties = new UXDesignProperties();
            $properties->target = $node;

            $element->createProperties($properties);

            $this->typeProperties[get_class($node)] = $properties;
        }

        $properties->target = $node;
        $properties->update();

        $pane->children->clear();

        foreach ($properties->getGroupPanes() as $groupPane) {
            $pane->children->add($groupPane);
        }
    }
}