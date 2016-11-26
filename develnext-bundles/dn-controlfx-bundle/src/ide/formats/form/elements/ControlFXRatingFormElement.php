<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\UXNode;
use php\gui\UXRating;

class ControlFXRatingFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'ControlFX';
    }

    public function getName()
    {
        return 'Рейтинг';
    }

    public function getIcon()
    {
        return "icons/develnext/bundle/controlfx/rating16.png";
    }


    public function isOrigin($any)
    {
        return $any instanceof UXRating;
    }

    public function getIdPattern()
    {
        return "rating%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $rating = new UXRating();
        return $rating;
    }
}