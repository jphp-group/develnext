<?php
namespace ide\marker;
use action\Animation;
use behaviour\custom\VibrationAnimationBehaviour;
use ide\Ide;
use php\gui\UXImageArea;
use php\gui\UXNode;
use php\gui\UXPopupWindow;

/**
 * Class ArrowPointMarker
 * @package ide\marker
 */
class ArrowPointMarker extends AbstractMarker
{
    /**
     * @var UXPopupWindow
     */
    protected $popup;

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
        $this->popup = new UXPopupWindow();
        $this->popup->on('click', function () {
            $this->hide();
        });

        $image = Ide::get()->getImage('icons/upArrow64.png')->image;
        $imageArea = new UXImageArea($image);
        $imageArea->autoSize = true;

        $this->popup->add($imageArea);
        $this->popup->size = [64, 64];

        $behaviour = new VibrationAnimationBehaviour();
        $behaviour->offsetY = -30;
        $behaviour->duration = 400;
        $behaviour->offsetX = 0;
        $behaviour->delay = 0;
        $behaviour->enabled = false;
        $behaviour->apply($this->popup);

        $this->vibration = $behaviour;
    }

    protected function showImpl()
    {
        $node = $this->target instanceof UXNode ? $this->target : $this->context->{$this->target};

        if ($node instanceof UXNode) {
            $this->popup->opacity = 0;
            $this->popup->showByNode($node, -($this->popup->width / 2) + $node->width / 2, $node->height);
            $this->vibration->enable();
            Animation::fadeIn($this->popup, 800);
        }
    }

    protected function hideImpl()
    {
        Animation::fadeOut($this->popup, 800, function () {
            $this->popup->hide();
            $this->vibration->disable();
        });
    }
}