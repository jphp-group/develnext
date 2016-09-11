<?php
namespace ide\ui;

use ide\systems\Cache;

class LazyImage implements LazyLoadingImage
{
    /**
     * @var string
     */
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    function getImage()
    {
        return Cache::getImage($this->path);
    }
}