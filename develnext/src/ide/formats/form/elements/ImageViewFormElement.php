<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXListView;
use php\gui\UXNode;

/**
 * Class ImageViewFormElement
 * @package ide\formats\form
 */
class ImageViewFormElement extends AbstractFormElement
{
    public function getName()
    {
        return 'Изображение';
    }

    public function getIcon()
    {
        return 'icons/image16.png';
    }

    public function getIdPattern()
    {
        return "image%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $image = new UXImageView();
        $image->fitWidth = 150;
        $image->fitHeight = 150;

        return $image;
    }

    public function getDefaultSize()
    {
        return [150, 150];
    }

    public function isOrigin($any)
    {
        return $any instanceof UXImageView;
    }
}
