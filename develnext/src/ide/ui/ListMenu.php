<?php
namespace ide\ui;

use ide\Ide;
use ide\utils\UiUtils;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\lib\arr;

class ListMenu extends UXListView
{
    protected $descriptionGetter = null;
    protected $nameGetter = null;
    protected $iconGetter = null;

    protected $nameThin = false;

    protected $thin = false;

    public function __construct()
    {
        parent::__construct();

        $this->classes->add('dn-list-menu');
        $this->fixedCellSize = 50;

        $this->setCellFactory(function (UXListCell $view, MenuViewable $page) {
            $this->cellRender($view, $page);
        });
    }

    /**
     * @return boolean
     */
    public function isNameThin()
    {
        return $this->nameThin;
    }

    /**
     * @param boolean $nameThin
     */
    public function setNameThin($nameThin)
    {
        $this->nameThin = $nameThin;
    }

    /**
     * @return bool
     */
    public function isThin()
    {
        return $this->thin;
    }

    /**
     * @param bool $thin
     */
    public function setThin($thin)
    {
        $this->thin = $thin;
        $this->fixedCellSize = $thin ? 40 : 50;
    }

    public function getDescriptionOfItem(MenuViewable $item)
    {
        return $this->descriptionGetter
            ? call_user_func($this->descriptionGetter, $item)
            : $item->getDescription();
    }

    public function getIconOfItem(MenuViewable $item)
    {
        return $this->iconGetter
            ? call_user_func($this->iconGetter, $item)
            : $item->getIcon();
    }

    public function getNameOfItem(MenuViewable $item)
    {
        return $this->nameGetter
            ? call_user_func($this->nameGetter, $item)
            : $item->getName();
    }

    /**
     * @param callable $descriptionGetter
     */
    public function setDescriptionGetter(callable $descriptionGetter)
    {
        $this->descriptionGetter = $descriptionGetter;
    }

    /**
     * @param callable $nameGetter
     */
    public function setNameGetter(callable $nameGetter)
    {
        $this->nameGetter = $nameGetter;
    }

    /**
     * @param callable $iconGetter
     */
    public function setIconGetter(callable $iconGetter)
    {
        $this->iconGetter = $iconGetter;
    }

    protected function cellRender(UXListCell $view, MenuViewable $page)
    {
        $view->text = null;

        $titleName = new UXLabel($this->getNameOfItem($page));
        $titleName->classes->add('dn-list-menu-title');

        if ($this->isNameThin()) {
            $titleName->style = '-fx-font-weight: normal;';
        }

        $titleName->style .= UiUtils::fontSizeStyle() . ";";

        $titleDescription = new UXLabel($this->getDescriptionOfItem($page));
        $titleDescription->classes->add('dn-list-menu-description');
        $titleDescription->style .= UiUtils::fontSizeStyle() . ";";

        $box = new UXHBox([$titleName]);
        $box->spacing = 0;

        $title = new UXVBox([$box, $titleDescription]);
        $title->spacing = 0;

        $list = [];

        $icon = $this->getIconOfItem($page);

        if ($icon) {
            $list[] = Ide::get()->getImage($icon);
        }

        $list[] = $title;

        UXHBox::setHgrow($title, 'ALWAYS');

        if ($page->getMenuCount() >= 0) {
            $label = new UXLabel($page->getMenuCount());
            $label->classes->add('dn-list-menu-count');
            $label->style = UiUtils::fontSizeStyle();

            $list[] = $label;
        }

        $line = new UXHBox($list);

        $line->spacing = 7;
        $line->padding = 5;
        $line->alignment = 'CENTER_LEFT';

        $view->text = null;
        $view->graphic = $line;
    }

    public function clear()
    {
        $this->items->clear();
    }

    public function add(MenuViewable $page)
    {
        $this->items->add($page);
    }

    public function refresh()
    {
        $selected = $this->selectedIndexes;

        $this->items->setAll(arr::of($this->items));

        $this->selectedIndexes = $selected;
    }
}