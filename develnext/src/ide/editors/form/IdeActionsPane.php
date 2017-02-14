<?php
namespace ide\editors\form;

use ide\misc\EventHandlerBehaviour;
use php\gui\designer\UXDesigner;
use php\gui\designer\UXDesignPane;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXComboBox;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\gui\UXTextField;
use php\gui\UXToggleButton;
use php\gui\UXToggleGroup;
use php\lang\IllegalArgumentException;
use php\lib\str;

class IdeActionsPane extends UXHBox
{
    protected $zoomSelect;
    use EventHandlerBehaviour;

    /**
     * @var UXDesigner
     */
    protected $designer;

    /**
     * @var UXToggleGroup
     */
    protected $snapTypeButtons;

    /**
     * @var UXToggleButton
     */
    protected $snapDotsButton;

    /**
     * @var UXToggleButton
     */
    protected $snapGridButton;

    /**
     * @var UXToggleButton
     */
    protected $snapEmptyButton;

    /**
     * @var UXTextField
     */
    protected $snapXInput;

    /**
     * @var UXTextField
     */
    protected $snapYInput;

    /**
     * @var UXDesignPane
     */
    private $designPane;

    public function __construct(UXDesigner $designer, UXDesignPane $designPane)
    {
        parent::__construct();

        $this->designer = $designer;
        $ui = $this;

        $ui->spacing = 4;
        $ui->padding = 8;
        $ui->paddingBottom = 4;
        $ui->height = 25;
        $ui->fillHeight = true;

        $this->snapTypeButtons = $group = new UXToggleGroup();

        $dotsButton = $this->snapDotsButton = new UXToggleButton();
        $dotsButton->graphic = ico('dots16');
        $dotsButton->toggleGroup = $group;
        $dotsButton->selected = true;

        $dotsButton->on('action', function () use ($dotsButton) {
            $this->designer->snapType = 'DOTS';
            $dotsButton->selected = true;
            $this->trigger('change');
        });

        $gridButton = $this->snapGridButton = new UXToggleButton();
        $gridButton->toggleGroup = $group;
        $gridButton->graphic = ico('grid16');

        $gridButton->on('action', function () use ($gridButton) {
            $this->designer->snapType = 'GRID';
            $gridButton->selected = true;
            $this->trigger('change');
        });

        $emptyButton = $this->snapEmptyButton = new UXToggleButton();
        $emptyButton->toggleGroup = $group;
        $emptyButton->graphic = ico('grayRect16');

        $emptyButton->on('action', function () use ($emptyButton) {
            $this->designer->snapType = 'HIDDEN';
            $emptyButton->selected = true;
            $this->trigger('change');
        });


        $ui->add($dotsButton);
        $ui->add($gridButton);
        $ui->add($emptyButton);
        $ui->add(new UXSeparator('VERTICAL'));

        $xTitle = new UXLabel('Сетка [X,Y]:');
        $xTitle->maxHeight = 999;
        $xInput = $this->snapXInput = new UXTextField();
        $xInput->width = 35;
        $xInput->maxHeight = 999;
        $xInput->text = $this->designer->snapSizeX;

        $xInput->observer('text')->addListener(function ($_, $value) {
            $value = (int) $value;
            if ($value > 0 && $this->designer->snapSizeX != $value) {
                $this->designer->snapSizeX = $value;
                $this->trigger('change');
            }
        });


        $ui->add($xTitle);
        $ui->add($xInput);

        /*$yTitle = new UXLabel('Сетка Y:');
        $yTitle->maxHeight = 999; */
        $yInput = $this->snapYInput = new UXTextField();
        $yInput->width = 35;
        $yInput->maxHeight = 999;
        $yInput->text = $this->designer->snapSizeY;

        $yInput->observer('text')->addListener(function ($_, $value) {
            $value = (int) $value;
            if ($value > 0 && $this->designer->snapSizeY != $value) {
                $this->designer->snapSizeY = $value;
                $this->trigger('change');
            }
        });

        //$ui->add($yTitle);
        $ui->add($yInput);

        $this->setSnapType($designer->snapType);
        $this->setSnapSizeX($designer->snapSizeX);
        $this->setSnapSizeY($designer->snapSizeY);

        $this->makeZoomPane();
        $this->makeAlignPane();
        $this->designPane = $designPane;
    }

    protected function makeZoomPane()
    {
        $this->add(new UXSeparator('VERTICAL'));
        $this->add($label = new UXLabel("Масштаб:"));
        $label->maxHeight = 999;

        $zoomSelect = $this->zoomSelect = new UXComboBox();
        $zoomList = [10, 25, 33, 50, 67, 75, 100, 125, 150, 200, 300, 400, 500];

        $this->zoomSelect->visibleRowCount = sizeof($zoomList);

        foreach ($zoomList as $zoom) {
            $this->zoomSelect->items->add("$zoom%");
        }

        $this->zoomSelect->value = "100%";
        $this->zoomSelect->width = 75;

        $this->zoomSelect->on('action', function () use ($zoomSelect, $zoomList) {
            $zoom = round($zoomList[$zoomSelect->selectedIndex] / 100, 2);
            $this->designPane->zoom = $zoom;
            $this->trigger('change');
        });

        $this->add($this->zoomSelect);
    }

    protected function makeAlignPane()
    {
        $this->add(new UXSeparator('VERTICAL'));

        foreach (['left', 'right', 'top', 'bottom', 'center', 'middle'] as $align) {
            $btn = new UXButton();
            $btn->tooltipText = 'Alignment (' . str::upperFirst($align) . ")";
            $btn->graphic = ico('align' . str::upperFirst($align) . '16');
            $btn->on('click', function () use ($align) {
                foreach ($this->designer->getSelectedNodes() as $node) {
                    if ($this->designer->getNodeLock($node)) continue;

                    call_user_func([$this, 'alignTo' . $align], $node);
                }

                $this->designer->update();
            });

            $this->add($btn);
        }
    }

    public function alignToLeft(UXNode $node)
    {
        $node->x = 0;
    }

    public function alignToTop(UXNode $node)
    {
        $node->y = 0;
    }

    public function alignToRight(UXNode $node)
    {
        $node->x = $node->parent->width - $node->boundsInParent['width'];
    }

    public function alignToBottom(UXNode $node)
    {
        $node->y = $node->parent->height - $node->boundsInParent['height'];
    }

    public function alignToCenter(UXNode $node)
    {
        $node->x = round($node->parent->width / 2 - $node->boundsInParent['width'] / 2);
    }

    public function alignToMiddle(UXNode $node)
    {
        $node->y = round($node->parent->height / 2 - $node->boundsInParent['height'] / 2);
    }

    public function setSnapType($type)
    {
        $this->lockHandles();

        try {
            switch ($this->designer->snapType = $type) {
                case 'GRID':
                    $this->snapTypeButtons->selected = $this->snapGridButton;
                    break;

                case 'HIDDEN':
                    $this->snapTypeButtons->selected = $this->snapEmptyButton;
                    break;

                default:
                    $this->snapTypeButtons->selected = $this->snapDotsButton;
                    break;
            }
        } catch (IllegalArgumentException $e) {
            $this->snapTypeButtons->selected = $this->snapDotsButton;
        }

        $this->unlockHandles();
    }

    public function setConfig(array $config)
    {
        if (isset($config['snapType'])) {
            $this->setSnapType($config['snapType']);
        }

        if (isset($config['snapSizeX'])) {
            $this->setSnapSizeX($config['snapSizeX']);
        }

        if (isset($config['snapSizeY'])) {
            $this->setSnapSizeY($config['snapSizeY']);
        }

        if (isset($config['zoom'])) {
            $this->setZoom($config['zoom']);
        }
    }

    public function getConfig()
    {
        return [
            'snapType'  => $this->getSnapType(),
            'snapSizeX' => $this->getSnapSizeX(),
            'snapSizeY' => $this->getSnapSizeY(),
            'zoom'      => $this->getZoom(),
        ];
    }

    public function getSnapType()
    {
        return $this->designer->snapType;
    }

    public function getZoom()
    {
        return $this->designPane->zoom * 100;
    }

    public function setZoom($zoom)
    {
        if ($zoom < 10) {
            $zoom = 100;
        }

        $this->lockHandles();
        $this->zoomSelect->value = "$zoom%";
        $this->designPane->zoom = $zoom / 100;
        $this->unlockHandles();
    }

    public function setSnapSizeX($x)
    {
        if ($x < 1) {
            $x = 8;
        }

        $this->lockHandles();
        $this->snapXInput->text = (int) $x;
        $this->designer->snapSizeX = (int) $x;
        $this->unlockHandles();
    }

    public function getSnapSizeX()
    {
        return $this->designer->snapSizeX;
    }

    public function setSnapSizeY($y)
    {
        if ($y < 1) {
            $y = 8;
        }

        $this->lockHandles();
        $this->snapYInput->text = (int) $y;
        $this->designer->snapSizeY = (int) $y;
        $this->unlockHandles();
    }

    public function getSnapSizeY()
    {
        return $this->designer->snapSizeY;
    }
}