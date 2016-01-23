<?php
namespace game;

use php\game\UXSprite;
use php\lib\Items;
use php\lib\Str;
use php\util\Flow;
use php\xml\DomElement;

/**
 * Class SpriteSpec
 * @package game
 */
class SpriteSpec
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $file;

    /**
     * @var int
     */
    public $frameWidth;

    /**
     * @var int
     */
    public $frameHeight;

    /**
     * @var int
     */
    public $speed = 12;

    /**
     * @var array of int[]
     */
    public $animations = [];

    /**
     * @var bool
     */
    public $metaCentred = true;

    /**
     * @var bool
     */
    public $metaAutoSize = false;

    /**
     * @var string
     */
    public $defaultAnimation;

    /**
     * @param $name
     * @param DomElement $element
     */
    function __construct($name, DomElement $element = null)
    {
        $this->name = $name;

        if ($element) {
            $this->file = $element->getAttribute('file');

            $this->frameWidth = (int)$element->getAttribute('frameWidth');
            $this->frameHeight = (int)$element->getAttribute('frameHeight');
            $this->defaultAnimation = $element->getAttribute('defaultAnimation');
            $this->metaCentred = (bool) $element->getAttribute('metaCentred');
            $this->metaAutoSize = (bool) $element->getAttribute('metaAutoSize');

            if ($element->hasAttribute('speed')) {
                $this->speed = (int)$element->getAttribute('speed');
            }

            /** @var DomElement $domAnimation */
            foreach ($element->findAll('./animation') as $domAnimation) {
                $name = $domAnimation->getAttribute('name');

                $this->animations[$name] = Flow::of(Str::split($domAnimation->getAttribute('indexes'), ','))->map(function ($one) {
                    return (int)trim($one);
                })->toArray();
            }
        }
    }

    public function removeFromAnimations(array $indexes)
    {
        foreach ($this->animations as $name => $list) {
            $this->removeFromAnimation($name, $indexes);
        }
    }

    public function removeFromAnimation($animation, array $indexes)
    {
        $this->animations[$animation] = Flow::of($this->animations[$animation])->find(function ($el) use ($indexes) {
            return !in_array($el, $indexes);
        })->toArray();
    }
}