<?php
namespace ide\ui;

use ide\Ide;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\lib\arr;

class ListMenu extends UXListView
{
    public function __construct()
    {
        parent::__construct();

        $this->classes->add('dn-list-menu');

        $this->setCellFactory(function (UXListCell $view, MenuViewable $page) {
            $this->cellRender($view, $page);
        });
    }

    protected function cellRender(UXListCell $view, MenuViewable $page)
    {
        $view->text = null;

        $titleName = new UXLabel($page->getName());
        $titleName->classes->add('dn-list-menu-title');

        $titleDescription = new UXLabel($page->getDescription());
        $titleDescription->classes->add('dn-list-menu-description');

        $box = new UXHBox([$titleName]);
        $box->spacing = 0;

        $title = new UXVBox([$box, $titleDescription]);
        $title->spacing = 0;

        $list = [];

        if ($page->getIcon()) {
            $list[] = Ide::get()->getImage($page->getIcon());
        }

        $list[] = $title;

        UXHBox::setHgrow($title, 'ALWAYS');

        if ($page->getMenuCount() >= 0) {
            $label = new UXLabel($page->getMenuCount());
            $label->classes->add('dn-list-menu-count');

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