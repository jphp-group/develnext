<?php
namespace ide\ui;

use ide\Ide;
use ide\utils\UiUtils;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabel;

class ListExtendedItem extends UXHBox
{
    private $title = "";
    private $description = "";
    private $icon = null;

    /**
     * @var UXLabel
     */
    private $uiTitle;

    /**
     * @var UXLabel
     */
    private $uiDescription;

    /**
     * @var UXImageView
     */
    private $uiIcon;

    /**
     * @var bool
     */
    private $titleThin = false;


    /**
     * ListExtendedItem constructor.
     * @param string $title
     * @param string $description
     * @param null $icon
     */
    public function __construct(string $title = '', string $description = '', $icon = null)
    {
        parent::__construct();

        $this->title = $title;
        $this->description = $description;

        $titleName = $this->uiTitle = new UXLabel($this->title);
        $titleName->classes->add('dn-list-menu-title');

        if ($this->isTitleThin()) {
            $titleName->style = '-fx-font-weight: normal;';
        }

        $titleName->style .= UiUtils::fontSizeStyle() . ";";

        $titleDescription = $this->uiDescription = new UXLabel($this->description);
        $titleDescription->classes->add('dn-list-menu-description');
        $titleDescription->style .= UiUtils::fontSizeStyle() . ";";

        $box = new UXHBox([$titleName]);
        $box->spacing = 0;

        $title = new UXVBox([$box, $titleDescription]);
        $title->spacing = 0;

        $list[] = $this->uiIcon = new UXImageView();

        $list[] = $title;

        UXHBox::setHgrow($title, 'ALWAYS');

        $this->children->addAll($list);

        $this->spacing = 7;
        $this->padding = 5;
        $this->alignment = 'CENTER_LEFT';

        $this->setIcon($icon);
    }

    /**
     * @return bool
     */
    public function isTitleThin(): bool
    {
        return $this->titleThin;
    }

    /**
     * @param bool $titleThin
     */
    public function setTitleThin(bool $titleThin)
    {
        $this->titleThin = $titleThin;
        $this->uiTitle->css('-fx-font-weight', $titleThin ? 'normal' : 'bold');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->uiTitle->text = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        $this->uiDescription->text = $description;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        if ($icon instanceof UXImage) {
            $this->uiIcon->image = $icon;
        } else if ($icon instanceof UXImageView) {
            $this->children->replace($this->uiIcon, $icon);
            $this->uiIcon = $icon;
        } else {
            if ($icon) {
                $icon = Ide::get()->getImage($icon);
                $this->children->replace($this->uiIcon, $icon);
                $this->uiIcon = $icon;
            }
        }

        $this->uiIcon->visible = !!$icon;
        $this->uiIcon->managed = !!$icon;
    }
}