<?php
namespace ide\autocomplete\ui;

use ide\autocomplete\AutoComplete;
use ide\autocomplete\AutoCompleteInsert;
use ide\autocomplete\AutoCompleteItem;
use ide\autocomplete\AutoCompleteType;
use ide\autocomplete\MethodAutoCompleteItem;
use ide\autocomplete\PropertyAutoCompleteItem;
use ide\autocomplete\VariableAutoCompleteItem;
use ide\Ide;
use ide\Logger;
use php\gui\designer\UXAbstractCodeArea;
use php\gui\designer\UXSyntaxTextArea;
use php\gui\event\UXKeyEvent;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;
use php\gui\UXApplication;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXPopupWindow;
use php\gui\UXSeparator;
use php\gui\UXWebView;
use php\lib\arr;
use php\lib\Char;
use php\lib\Items;
use php\lib\Str;
use php\util\Flow;
use script\TimerScript;
use timer\AccurateTimer;

class AutoCompletePane
{
    /**
     * @var UXPopupWindow
     */
    protected $ui;

    /**
     * @var UXPopupWindow
     */
    protected $uiDescription;

    /**
     * @var UXSyntaxTextArea
     */
    protected $area;

    /**
     * @var AutoComplete
     */
    protected $complete;

    /**
     * @var UXListView
     */
    protected $list;

    /**
     * @var bool
     */
    protected $visible;

    /**
     * @var AutoCompleteType[]
     */
    protected $types = [];

    /**
     * @var bool
     */
    protected $shown = false;

    /**
     * @var null
     */
    protected $lastString = null;

    protected $lock = false;

    /**
     * @var bool
     */
    protected $inserted = false;

    /**
     * @param UXSyntaxTextArea|UXAbstractCodeArea $area
     * @param AutoComplete $complete
     */
    public function __construct($area, AutoComplete $complete)
    {
        $this->area = $area;
        $this->complete = $complete;
        $this->makeUi();
        $this->init();
    }

    protected function init()
    {
        $this->area->observer('focused')->addListener(function ($old, $new) {
            if (!$new) {
                $this->hide();
            }
        });

        $this->area->on('mouseDown', function () {
            $this->hide();
        });

        $this->area->on('keyDown', function (UXKeyEvent $e) {
            $this->area->data('oldCaretPosition', $this->area->caretPosition);

            switch ($e->codeName) {
                case 'Up':
                    if ($this->doUp()) {
                        $e->consume();
                    }
                    break;

                case 'Down':
                    if ($this->doDown()) {
                        $e->consume();
                    }
                    break;

                case 'Enter':
                    if ($this->doPick()) {
                        $e->consume();
                    }
                    break;

                case 'Esc':
                    $this->ui->hide();
                    $e->consume();
                    break;

                default:
                    $this->complete->update($this->area->text, $this->area->caretPosition, $this->area->caretLine, $this->area->caretOffset);
                    break;
            }

        }, __CLASS__);

        $this->area->on('keyUp', function (UXKeyEvent $e) {
            if ($e->controlDown || $e->altDown) {
                return;
            }

            switch ($e->codeName) {
                case 'Up':
                case 'Down':
                    return;

                case 'Left':
                case 'Right':
                    return;
            }

            if ($this->inserted) {
                $this->inserted = false;
                return;
            }

            if ($this->lock) {
                $this->lock = false;
                return;
            }

            if ($this->area instanceof UXSyntaxTextArea) {
                list($x, $y) = $this->area->getCaretScreenPosition();
            } else {
                $x = $y = null;
            }

            if ($string = $this->getString()) {
                $items = null;

                if ($string != $this->lastString) {
                    $region = $this->complete->findRegion($this->area->caretLine, $this->area->caretOffset);
                    $types = $this->complete->identifyType($string, $region);

                    if (Items::keys($this->types) != $types) {
                        $this->types = [];

                        foreach ($types as $type) {
                            //if (!$this->hasType($type)) {
                            $this->addType($type);
                            //}
                        }
                    }

                    $items = $this->makeItems($this->getString(true));
                }

                $this->lastString = $string;

                UXApplication::runLater(function () use ($x, $y, $string, $items) {
                    if ($items !== null) {
                        $this->list->items->clear();
                        $this->list->items->addAll($items);
                        $this->list->selectedIndex = 0;

                        //Logger::debug("Autocomplete list updated.");
                    }

                    if ($this->list->items->count) {
                        $this->show($x + 45, $y + 20);
                    } else {
                        //Logger::debug("No auto complete items for: $string");
                        $this->hide();
                    }
                });
            } else {
                $this->hide();
            }
        }, __CLASS__);
    }

    /**
     * @var AccurateTimer
     */
    protected $showTimer;

    public function show($x, $y) {
        if ($this->showTimer) {
            $this->showTimer->free();
        }

        $this->showTimer = waitAsync($this->shown ? 1 : 100, function () use ($x, $y) {
            $this->showTimer = null;

            if (!$this->list->items->count) {
                return;
            }

            if (!$this->shown) {
                $this->list->selectedIndex = 0;
            }

            $size = min([$this->list->items->count, 10]);

            $this->ui->layout->maxHeight = $size * ($this->list->fixedCellSize) + 6 + 2 + $this->list->fixedCellSize;

            if ($this->area instanceof UXAbstractCodeArea) {
                $this->ui->show($this->area->form);
            } else {
                $this->ui->show($this->area->form, $x, $y);
            }

            $this->shown = true;
        });
    }

    public function hide() {
        UXApplication::runLater(function () {
            $this->shown = false;

            $this->ui->hide();
            $this->uiDescription->hide();
        });
    }

    protected function restoreCaret()
    {
        $this->area->caretPosition = $this->area->data('oldCaretPosition');
    }

    protected function doUp()
    {
        if ($this->shown) {
            UXApplication::runLater(function () {
                $this->list->selectedIndex -= 1;

                if ($this->list->selectedIndex == -1) {
                    $this->list->selectedIndex = $this->list->items->count - 1;
                }
            });
            return true;
        }
    }

    protected function doDown()
    {
        if ($this->shown) {
            UXApplication::runLater(function () {
                $this->list->selectedIndex += 1;

                if ($this->list->selectedIndex == -1) {
                    $this->list->selectedIndex = 0;
                }
            });
            return true;
        }
    }

    protected function doPick()
    {
        //Logger::debug("do pick");

        if ($this->shown) {
            UXApplication::runLater(function () {
                $this->hide();

                /** @var AutoCompleteItem $selected */
                $selected = Items::first($this->list->selectedItems);

                $prefix = $this->getString(true);

                $insert = $selected->getInsert();
                Logger::debug("Insert to caret: " . $insert);

                if (!is_string($insert) && is_callable($insert)) {
                    $in = new AutoCompleteInsert($this->area);
                    $insert($in);

                    $insert = $in->getValue();
                }

                $this->area->caretPosition -= str::length($prefix);
                $this->area->deleteText($this->area->caretPosition, $this->area->caretPosition + str::length($prefix));

                $this->area->insertToCaret($insert);
            });

            $this->lock = true;
            return true;
        }
    }

    public function hasType($name)
    {
        return isset($this->types[$name]);
    }

    public function addType($name)
    {
        $type = $this->complete->fetchType($name);

        if ($type) {
            $this->types[is_string($name) ? $name : str::uuid()] = $type;
        }
    }

    public function getString($onlyName = false)
    {
        $text = $this->area->text;

        $i = $this->area->caretPosition;

        $string = '';
        $braces = [];

        if (Char::isSpace($text[$i - 1])) {
            return $string;
        }

        while ($i-- >= 0) {
            $ch = $text[$i];

            if (Char::isPrintable($ch)
                && (Char::isLetterOrDigit($ch)) || $ch == '_') {
                $string .= $ch;
            } else {
                if ($onlyName /*&& $ch != '$'*/) { // todo refactor for $
                    break;
                } else {
                    $string .= $ch;
                }
            }
        }

        return Str::reverse($string);
    }

    public function add($string)
    {
        $this->list->items->add($string);
    }

    protected function updateDescription(AutoCompleteItem $item)
    {
        //$cell = new UXListCell();
        $this->uiDescription->autoFix = true;
        $name = $item->getName();

        if ($item instanceof MethodAutoCompleteItem) {
            $name .= '()';
        }

        if ($item instanceof VariableAutoCompleteItem) {
            $name = "\${$name}";
        }

        $this->uiDescription->layout->lookup('.title')->text = $name;
        $this->uiDescription->layout->lookup('.description')->text = $item->getDescription();

        /** @var UXHBox $header */
        $header = $this->uiDescription->layout->lookup('.header');
        if ($header->children->count > 1) {
            $header->children->removeByIndex(0);
        }

        if ($ic = $this->getImageOfItem($item)) {
            $header->children->insert(0, $ic);
        }
        //$this->makeItemUi($cell, $item);

        /** @var UXVBox $content */
        $content = $this->uiDescription->layout->lookup('.content');
        $content->children->clear();

        $contentValue = $item->getContent();

        if ($contentValue['DEF']) {
            $content->add(new UXSeparator());
            $content->add(new UXLabel($contentValue['RU'] ?: $contentValue['DEF']));
        }
    }

    private function makeDescriptionUi()
    {
        $ui = new UXVBox();
        $ui->spacing = 5;
        $ui->padding = 10;
        $ui->maxWidth = 650;
        $ui->focusTraversable = false;
        $ui->padding = 3;

        $title = new UXLabel("Title");
        $title->classes->add('title');

        $header = new UXHBox([$title]);
        $header->classes->add('header');
        $ui->add($header);

        $desc = new UXLabel("Description");
        $desc->classes->add('description');
        $ui->add($desc);

        $content = new UXVBox();
        $content->padding = $content->spacing = 5;
        $content->classes->add('content');
        $ui->add($content);

        $ui->classes->add('dn-autocomplete-description');

        $win = new UXPopupWindow();
        $win->layout = $ui;

        $this->uiDescription = $win;
    }

    private function makeUi()
    {
        $this->makeDescriptionUi();

        $ui = new UXVBox();
        $ui->height = 150;
        $ui->maxWidth = 650;
        $ui->focusTraversable = false;
        $ui->padding = 3;

        $list = new UXListView();
        $list->on('action', function () use ($list) {
            if ($list->selectedIndex > -1) {
                if (!$this->uiDescription->visible) {
                    $this->uiDescription->show($this->area->form, $this->ui->x + $this->ui->width + 3, $this->ui->y + 3);
                }

                $this->updateDescription($list->selectedItem);
            } else {
                $this->uiDescription->hide();
            }
        });

        $list->maxHeight = 9999;
        $list->fixedCellSize = 20;
        $list->classes->addAll(['hide-hor-scroll', 'dn-console-list', 'dn-autocomplete']);
        $list->style = '-fx-background-insets: 0; -fx-focus-color: -fx-control-inner-background; -fx-faint-focus-color: -fx-control-inner-background;';
        $list->width = 400;

        $ui->add($list);
        $ui->focusTraversable = false;
        UXVBox::setVgrow($list, 'ALWAYS');

        $list->setCellFactory(function (UXListCell $cell, AutoCompleteItem $item) {
            $cell->graphic = null;
            $cell->text = null;
            $this->makeItemUi($cell, $item);
        });

        $this->list = $list;

        $ui->observer('visible')->addListener(function ($old, $new) {
            $this->visible = $new;
        });

        $win = new UXPopupWindow();
        $win->layout = $ui;
        /*$win->style = 'TRANSPARENT';
        $win->opacity = 0.7;*/

        $v = function () {
            $this->uiDescription->x = $this->ui->x + $this->ui->width + 3;
            $this->uiDescription->y = $this->ui->y + 3;
        };
        $win->observer('x')->addListener($v);
        $win->observer('y')->addListener($v);

        $list->on('click', function () {
            $this->doPick();
        });

        $list->on('keyDown', function (UXKeyEvent $e) {
            switch ($e->codeName) {
                case 'Enter':
                    if ($this->doPick()) {
                        $e->consume();
                    }
                    break;

                case 'Esc':
                    $this->hide();
                    $e->consume();
                    $this->lock = true;
                    break;
            }
        });


        if ($this->area instanceof UXAbstractCodeArea) {
            $this->area->popupWindow = $win;
        }

        return $this->ui = $win;
    }

    private function makeItems($prefix = '')
    {
        $flow = Flow::ofEmpty();

        $region = $this->complete->findRegion($this->area->caretLine, $this->area->caretOffset);

        foreach ($this->types as $type) {
            $flow = $flow
                ->append($type->getStatements($this->complete, $region))
                ->append($type->getConstants($this->complete, $region))
                ->append($type->getMethods($this->complete, $region))
                ->append($type->getProperties($this->complete, $region))
                ->append($type->getVariables($this->complete, $region));
        }

        if ($prefix) {
            $flow = $flow->find(function (AutoCompleteItem $one) use ($prefix) {
                return Str::contains(str::lower($one->getName()), str::lower($prefix));
            });
        }

        $items = $flow->sort(function (AutoCompleteItem $one, AutoCompleteItem $two) use ($prefix) {
            if ($one->getName() == $two->getName()) {
                return 0;
            }

            if ($one->getName() == $prefix) { return -1; }
            if ($two->getName() == $prefix) { return 1; }

            if (str::startsWith($one->getName(), $prefix) && str::startsWith($two->getName(), $prefix)) {
                // nop.
            } else {
                if (str::startsWith($one->getName(), $prefix)) {
                    return -1;
                }

                if (str::startsWith($two->getName(), $prefix)) {
                    return 1;
                }
            }

            return Str::compare($one->getName(), $two->getName());
        });

        if (arr::first($items) == $prefix && sizeof($items) < 2) {
            return [];
        }

        return $items;
    }

    protected function getImageOfItem(AutoCompleteItem $item)
    {
        $icon = Ide::get()->getImage($item->getIcon() ?: $item->getDefaultIcon(), [16, 16]);

        if ($icon) {
            UXHBox::setMargin($icon, [0, 5, 0, 0]);
        }

        return $icon;
    }

    protected function makeItemUi(UXListCell $cell, AutoCompleteItem $item)
    {
        $label = new UXLabel($item->getName());
        $label->textColor = UXColor::of('black');
        $label->style = $item->getStyle();

        $icon = $this->getImageOfItem($item);

        if (!$item->getDescription()) {
            $cell->graphic = $icon ? new UXHBox([$icon, $label]) : $label;
        } else {
            $hintLabel = new UXLabel($item->getDescription() ? ": {$item->getDescription()}" : "");
            $hintLabel->textColor = UXColor::of('gray');

            if ($icon) {
                if ($item->getDescription()) {
                    $dots = new UXLabel(": ");
                    $dots->textColor = $hintLabel->textColor;
                    $hintLabel->text = $item->getDescription();

                    $cell->graphic = new UXHBox([$icon, $label, $dots, $hintLabel]);
                } else {
                    $cell->graphic = new UXHBox([$icon, $label]);
                }
            } else {
                $cell->graphic = new UXHBox([$label, $hintLabel]);
            }
        }

        if ($item instanceof VariableAutoCompleteItem) {
            $label->text = "\${$label->text}";
            $label->textColor = UXColor::of('blue');
        }

        if ($item instanceof MethodAutoCompleteItem) {
            if ($item->isFunction()) {
                $label->text = "{$label->text}()";
            } else {
                $label->text = "{$label->text}()";
            }

            $label->textColor = UXColor::of('black');
        }

        if ($item instanceof PropertyAutoCompleteItem) {
            $label->text = "{$label->text}";
            $label->textColor = UXColor::of('green');
        }
    }
}