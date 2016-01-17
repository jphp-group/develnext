<?php
namespace ide\marker;
use action\Animation;
use behaviour\custom\VibrationAnimationBehaviour;
use ide\Ide;
use php\gui\UXImageArea;
use php\gui\UXNode;
use php\gui\UXPopupWindow;
use php\gui\UXScreen;
use php\gui\UXTooltip;

/**
 * Class ArrowPointMarker
 * @package ide\marker
 */
class ArrowPointMarker extends AbstractMarker
{
    /**
     * @var int
     */
    public $radius = 30;

    /**
     * @var int
     */
    public $animationSpeed = 400;

    /**
     * @var string
     */
    public $direction = 'AUTO';

    /**
     * @var string
     */
    public $tooltipText;

    /**
     * @var UXPopupWindow
     */
    protected $popup;

    /**
     * @var UXTooltip
     */
    protected $tooltip;

    /**
     * @var VibrationAnimationBehaviour
     */
    protected $vibration;

    /**
     * @param mixed $target
     */
    public function __construct($target)
    {
        parent::__construct($target);

        $this->makeUi();
    }

    protected function makeUi()
    {
        $this->tooltip = new UXTooltip();
        $this->tooltip->cursor = 'HAND';

        $this->popup = new UXPopupWindow();
        $this->popup->cursor = 'HAND';

        $image = Ide::get()->getImage('icons/upArrow64.png')->image;
        $imageArea = new UXImageArea($image);
        $imageArea->id = 'imageArea';
        $imageArea->autoSize = true;
        $imageArea->cursor = $this->popup->cursor;

        $imageArea->on('click', function () {
            $this->trigger('click');
            $this->hide();
        });

        $behaviour = new VibrationAnimationBehaviour();
        $this->popup->add($imageArea);
        $this->popup->size = $imageArea->size;

        $behaviour->delay = 0;
        $behaviour->enabled = false;
        $behaviour->apply($this->popup);

        $this->vibration = $behaviour;
    }

    protected function getDetectedDirection(UXNode $node)
    {
        $direction = $this->direction;

        $x = $node->screenX;
        $y = $node->screenY;
        $screen = UXScreen::getPrimary()->visualBounds;

        if ($direction == 'AUTO') {
            if ($x < $this->radius + $this->popup->width) {
                $direction = 'LEFT';
            } elseif ($x > $screen['width'] - $this->radius - $this->popup->width) {
                $direction = 'RIGHT';
            } elseif ($y > $screen['height'] - $this->radius - $this->popup->height) {
                $direction = 'DOWN';
            } elseif ($y < $this->radius + $this->popup->height) {
                $direction = 'UP';
            } else {
                $direction = 'LEFT';
            }
        }

        return $direction;
    }

    protected function showImpl(UXNode $node)
    {
        if ($node instanceof UXNode) {
            $node->classes->add('point-marker');

            $direction = $this->getDetectedDirection($node);

            $this->popup->opacity = 0;

            /** @var UXNode $imageArea */
            $imageArea = $this->popup->{'imageArea'};
            $this->vibration->duration = $this->animationSpeed;

            $bounds = $node->boundsInParent;

            $offsetX = $offsetY = 0;

            $tooltip = $this->tooltip;
            $tooltip->text = $this->tooltipText;
            $tooltip->on('click', function () use ($tooltip) {
                $this->trigger('click');
                $this->hide();
            });

            $tooltipX = $tooltipY = 0;

            $this->vibration->offsetX = $this->vibration->offsetY = 0;

            switch ($direction) {
                case 'UP':
                    $this->vibration->offsetY = -1 * $this->radius;
                    $imageArea->rotate = 0;

                    $offsetX = -($this->popup->width / 2) + $bounds['width'] / 2;
                    $offsetY = $bounds['height'];

                    $tooltipY = $node->screenY + $this->popup->height + $this->radius + $bounds['height'] - 10;
                    $tooltipX = $node->screenX;

                    $tooltip->on('show', function () use ($tooltip, $bounds) {
                        $tooltip->x += -(($tooltip->width + ($tooltip->layout->paddingLeft * 2)) / 2) + $bounds['width'] / 2;
                    });
                    break;
                case 'DOWN':
                    $this->vibration->offsetY = 1 * $this->radius;
                    $imageArea->rotate = 180;

                    $offsetX = -($this->popup->width / 2) + $bounds['width'] / 2;
                    $offsetY = -($this->popup->height);

                    $tooltipY = $node->screenY - $this->popup->height - $this->radius - $bounds['height'] + 10;
                    $tooltipX = $node->screenX;

                    $tooltip->on('show', function () use ($tooltip, $bounds) {
                        $tooltip->x += -($tooltip->width / 2);
                    });
                    break;
                case 'LEFT':
                    $this->vibration->offsetX = -1 * $this->radius;
                    $imageArea->rotate = 270;

                    $offsetX = $bounds['width'];
                    $offsetY = -($this->popup->height / 2) + $bounds['height'] / 2;

                    $tooltipY = $node->screenY + $bounds['height'] / 2 + $this->popup->height / 2 + 10;
                    $tooltipX = $node->screenX + $bounds['width'];
                    break;
                case 'RIGHT':
                    $this->vibration->offsetX = 1 * $this->radius;
                    $imageArea->rotate = 90;

                    $offsetX = -$this->popup->width;
                    $offsetY = -($this->popup->height / 2) + $bounds['height'] / 2;

                    $tooltipY = $node->screenY + $this->popup->height;
                    $tooltipX = $node->screenX - $this->popup->height - $this->radius;

                    $tooltip->on('show', function () use ($tooltip, $bounds) {
                        $tooltip->x += -($tooltip->width / 2);
                    });
                    break;
            }


            $this->popup->showByNode($node, $offsetX, $offsetY);

            $this->vibration->enable();
            Animation::fadeIn($this->popup, 800, function () use ($tooltip, $tooltipX, $tooltipY) {
                $tooltip->opacity = 0;
                $tooltip->show($this->popup, $tooltipX, $tooltipY);
                Animation::fadeIn($tooltip, 400);
            });
        }
    }

    protected function hideImpl(UXNode $node)
    {
        $node->classes->remove('point-marker');

        $this->tooltip->layout->mouseTransparent = true;
        $this->popup->layout->mouseTransparent = true;

        Animation::fadeOut($this->tooltip, 800);
        Animation::fadeOut($this->popup, 800, function () {
            $this->popup->hide();
            $this->vibration->disable();

            $this->tooltip->layout->mouseTransparent = false;
            $this->popup->layout->mouseTransparent = false;
        });
    }
}