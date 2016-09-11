<?php
namespace ide\formats\sprite;

use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\ui\LazyLoadingImage;

class SpritePreviewImage implements LazyLoadingImage
{
    /**
     * @var string
     */
    private $name;

    /**
     * SpritePreviewImage constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    function getImage()
    {
        $gui = GuiFrameworkProjectBehaviour::get();

        if ($gui) {
            if ($manager = $gui->getSpriteManager()) {
                return $manager->getSpritePreview($this->name);
            }
        }
    }
}