<?php
namespace ide\editors;

use php\gui\designer\UXDesigner;
use php\gui\designer\UXDesignPane;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXPane;
use php\gui\layout\UXScrollPane;
use php\gui\UXDialog;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\io\File;
use php\lib\String;
use php\util\Configuration;

/**
 * Class FormEditor
 * @package ide\editors
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

    public function __construct($file)
    {
        parent::__construct($file);
    }

    public function isValid()
    {
        if (String::endsWith($this->file, '.fxml')) {
            return true;
        }

        return false;
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
        $loader = new UXLoader();
        $this->layout = $loader->load($this->file);
    }

    public function save()
    {

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

        foreach ($this->layout->children as $node) {
            $this->designer->registerNode($node);
        }

        $area->add($designPane);

        return $viewer;
    }
}