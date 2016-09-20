<?php
namespace ide\ui;
use php\gui\UXImageArea;
use php\gui\layout\UXVBox;
use php\gui\UXLabelEx;
use php\gui\UXImage;
use behaviour\StreamLoadableBehaviour;
use php\io\Stream;

/**
 * Class ImageBox
 * @package ide\ui
 */
class ImageBox extends UXVBox implements StreamLoadableBehaviour
{
    /**
     * @var UXImageArea
     */
    protected $imageArea;

    /**
     * @var UXLabelEx
     */
    protected $titleLabel;

    /**
     * ImageBox constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        parent::__construct();

        $this->alignment = 'TOP_CENTER';
        $this->classes->add('dn-list-item');

        $item = new UXImageArea();
        $item->size = [$width, $height];

        $item->centered = true;
        $item->stretch = true;
        $item->smartStretch = true;
        $item->proportional = true;

        $this->add($item);
        $this->imageArea = $item;

        $nameLabel = new UXLabelEx();
        $nameLabel->textAlignment = 'CENTER';
        $nameLabel->alignment = 'TOP_CENTER';
        $nameLabel->paddingTop = 5;
        $nameLabel->width = $item->width;

        $this->add($nameLabel);
        $this->titleLabel = $nameLabel;
    }

    public function setImage(UXImage $image = null)
    {
        $this->imageArea->image = $image;
    }

    public function getImage()
    {
        return $this->imageArea->image;
    }

    public function setTitle($title, $style = '')
    {
        $this->titleLabel->text = $title;
        $this->titleLabel->style .= $style;
        $this->titleLabel->tooltipText = $title;
    }

    public function getTitle()
    {
        return $this->titleLabel->text;
    }

    /**
     * @param $path
     * @return mixed
     */
    function loadContentForObject($path)
    {
        return new UXImage(Stream::of($path));
    }

    /**
     * @param $content
     * @return mixed
     */
    function applyContentToObject($content)
    {
        $this->imageArea->image = $content;
    }

    /**
     * @param string $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->titleLabel->tooltipText = $tooltip;
    }
}