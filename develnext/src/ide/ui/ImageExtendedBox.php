<?php
namespace ide\ui;

use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXImageArea;
use php\gui\UXLabel;
use php\gui\UXLabelEx;
use php\gui\UXImage;
use behaviour\StreamLoadableBehaviour;
use php\io\Stream;

/**
 * Class ImageBox
 * @package ide\ui
 */
class ImageExtendedBox extends UXHBox implements StreamLoadableBehaviour
{
    /**
     * @var UXImageArea
     */
    protected $imageArea;

    /**
     * @var UXLabel
     */
    protected $titleLabel;

    /**
     * @var UXLabel
     */
    protected $descriptionLabel;

    /**
     * ImageBox constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        parent::__construct([], 10);

        $this->alignment = 'CENTER_LEFT';
        $this->classes->add('dn-list-item');

        $item = new UXImageArea();
        $item->size = [$width, $height];

        $item->centered = true;
        $item->stretch = true;
        $item->smartStretch = true;
        $item->proportional = true;

        $this->add($item);
        $this->imageArea = $item;

        $box = new UXVBox([]);
        $box->alignment = 'CENTER_LEFT';

        $nameLabel = new UXLabel();
        $nameLabel->font->bold = true;

        $box->add($nameLabel);
        $this->titleLabel = $nameLabel;

        $descLabel = new UXLabel();
        $box->add($descLabel);
        $this->descriptionLabel = $descLabel;

        $this->add($box);
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

    public function setDescription($description, $style = '')
    {
        $this->descriptionLabel->text = $description;
        $this->descriptionLabel->tooltipText = $description;
        $this->descriptionLabel->style = $style;
    }

    public function getTitle()
    {
        return $this->titleLabel->text;
    }

    public function getDescription()
    {
        return $this->descriptionLabel->text;
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
        $this->descriptionLabel->tooltipText = $tooltip;
    }
}