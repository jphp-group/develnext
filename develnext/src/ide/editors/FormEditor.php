<?php
namespace ide\editors;

use ide\editors\form\FormElementTypePane;
use ide\editors\menu\ContextMenu;
use ide\formats\AbstractFormFormat;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\FormFormat;
use ide\formats\PhpCodeFormat;
use ide\forms\MainForm;
use ide\Ide;
use ide\project\ProjectFile;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDesignPane;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXMouseEvent;
use php\gui\framework\DataUtils;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTooltip;
use php\io\File;
use php\lib\Items;
use php\lib\Str;
use php\lib\String;
use php\time\Time;
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
     * @var string
     */
    protected $codeFile;

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
     * @var CodeEditor
     */
    protected $codeEditor;

    /**
     * @var UXDesignProperties[]
     */
    protected static $typeProperties = [];

    public function __construct($file, AbstractFormDumper $dumper)
    {
        parent::__construct($file);

        $this->formDumper = $dumper;

        $phpFile = $file;

        if (Str::endsWith($phpFile, '.fxml')) {
            $phpFile = Str::sub($phpFile, 0, Str::length($phpFile) - 5);
        }

        $phpFile .= '.php';

        if ($file instanceof ProjectFile) {
            if ($link = $file->findLinkByExtension('php')) {
                $phpFile = $link;
            }
        }

        $this->codeFile = $phpFile;
        $this->codeEditor = Ide::get()->getRegisteredFormat(PhpCodeFormat::class)->createEditor($phpFile);
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

        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->load();
        }
    }

    public function save()
    {
        $this->formDumper->save($this);

        if (File::of($this->codeFile)->exists()) {
            $this->codeEditor->save();
        }
    }

    public function close()
    {
        parent::close();

        $this->updateProperties(null);
    }


    public function makeUi()
    {
        if (!$this->layout) {
            throw new \Exception("Cannot open unloaded form");
        }

        $codeEditor = $this->makeCodeEditor();
        $designer = $this->makeDesigner();

        $tabs = new UXTabPane();
        $tabs->side = 'LEFT';
        $tabs->tabClosingPolicy = 'UNAVAILABLE';

        $codeTab = new UXTab();
        $codeTab->text = 'Исходный код';
        $codeTab->content = $codeEditor;
        $codeTab->style = '-fx-cursor: hand;';
        $codeTab->graphic = Ide::get()->getImage($this->codeEditor->getIcon());
        $codeTab->tooltip = UXTooltip::of($this->codeFile);

        $eventsTab = new UXTab();
        $eventsTab->text = 'События';

        $designerTab = new UXTab();
        $designerTab->text = 'Дизайн';
        $designerTab->content = $designer;
        $designerTab->style = '-fx-cursor: hand;';
        $designerTab->graphic = Ide::get()->getImage($this->getIcon());

        $tabs->tabs->add($designerTab);

        if (File::of($this->codeFile)->exists()) {
            $tabs->tabs->add($codeTab);
        }

        return $tabs;
    }

    protected function makeCodeEditor()
    {
        return $this->codeEditor->makeUi();
    }

    protected function makeDesigner()
    {
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

            if (!$node->id) {
                $node->id = 'element' . (sizeof($this->designer->getNodes()) + 1);
            }

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

            $data = DataUtils::get($node);

            foreach ($selected->getInitProperties() as $key => $property) {
                if ($property['virtual']) {
                    $data->set($key, $property['value']);
                } else if ($key !== 'width' && $key !== 'height') {
                    $node->{$key} = $property['value'];
                }
            }
        } else {
            $this->updateProperties($this);
        }
    }

    protected function _onChanged()
    {
        $this->save();
        $this->_onNodePick();
    }

    protected function _onNodePick()
    {
        $node = $this->designer->pickedNode;

        if ($node) {
            Timer::run(50, function () use ($node) {
                $this->updateProperties($node);
            });
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

    public static function initializeElement(AbstractFormElement $element)
    {
        $properties = new UXDesignProperties();
        $element->createProperties($properties);

        return static::$typeProperties[get_class($element)] = $properties;
    }

    protected function updateProperties($node)
    {
        $element = $this->format->getFormElement($node);

        /** @var MainForm $mainForm */
        $mainForm = Ide::get()->getMainForm();
        $pane = $mainForm->getPropertiesPane();

        $properties = $element ? static::$typeProperties[get_class($element)] : null;

        if ($properties) {
            $properties->target = $node;
            $properties->update();
        }

        $pane->children->clear();

        if ($properties) {
            foreach ($properties->getGroupPanes() as $groupPane) {
                $pane->children->add($groupPane);
            }
        }

        if (!$properties || !$properties->getGroupPanes()) {
            $hint = new UXLabel('Список пуст.');

            if ($node === null) {
                $hint->text = '...';
            }

            $hint->style = '-fx-font-style: italic;';
            $hint->maxSize = [10000, 10000];
            $hint->padding = 20;
            $hint->alignment = 'BASELINE_CENTER';

            $pane->children->add($hint);
        }
    }
}