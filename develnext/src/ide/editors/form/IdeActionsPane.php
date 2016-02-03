<?php
namespace ide\editors\form;

use ide\misc\EventHandlerBehaviour;
use php\gui\designer\UXDesigner;
use php\gui\layout\UXHBox;
use php\gui\UXLabel;
use php\gui\UXSeparator;
use php\gui\UXTextField;
use php\gui\UXToggleButton;
use php\gui\UXToggleGroup;
use php\lang\IllegalArgumentException;

class IdeActionsPane extends UXHBox
{
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
     * @var UXTextField
     */
    protected $snapXInput;

    /**
     * @var UXTextField
     */
    protected $snapYInput;

    public function __construct(UXDesigner $designer)
    {
        parent::__construct();

        $this->designer = $designer;
        $ui = $this;

        $ui->spacing = 4;
        $ui->padding = 10;
        $ui->paddingBottom = 5;
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

        $ui->add($dotsButton);
        $ui->add($gridButton);
        $ui->add(new UXSeparator('VERTICAL'));

        $xTitle = new UXLabel('Сетка X:');
        $xTitle->maxHeight = 999;
        $xInput = $this->snapXInput = new UXTextField();
        $xInput->width = 40;
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

        $yTitle = new UXLabel('Сетка Y:');
        $yTitle->maxHeight = 999;
        $yInput = $this->snapYInput = new UXTextField();
        $yInput->width = 40;
        $yInput->maxHeight = 999;
        $yInput->text = $this->designer->snapSizeY;

        $yInput->observer('text')->addListener(function ($_, $value) {
            $value = (int) $value;
            if ($value > 0 && $this->designer->snapSizeY != $value) {
                $this->designer->snapSizeY = $value;
                $this->trigger('change');
            }
        });

        $ui->add($yTitle);
        $ui->add($yInput);

        $this->setSnapType($designer->snapType);
        $this->setSnapSizeX($designer->snapSizeX);
        $this->setSnapSizeY($designer->snapSizeY);
    }

    public function setSnapType($type)
    {
        $this->lockHandles();

        try {
            switch ($this->designer->snapType = $type) {
                case 'GRID':
                    $this->snapTypeButtons->selected = $this->snapGridButton;
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
    }

    public function getConfig()
    {
        return [
            'snapType'  => $this->getSnapType(),
            'snapSizeX' => $this->getSnapSizeX(),
            'snapSizeY' => $this->getSnapSizeY(),
        ];
    }

    public function getSnapType()
    {
        return $this->designer->snapType;
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