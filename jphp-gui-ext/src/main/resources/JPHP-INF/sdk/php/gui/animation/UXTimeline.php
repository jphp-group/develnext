<?php
namespace php\gui\animation;
use php\gui\UXList;

/**
 * Class UXTimeline
 * @package php\gui\animation
 */
class UXTimeline extends UXAnimation
{
    /**
     * @readonly
     * @var UXList of UXKeyFrame
     */
    public $keyFrames;

    /**
     * @param UXKeyFrame[] $keyFrames
     */
    public function __construct(array $keyFrames = [])
    {
    }
}