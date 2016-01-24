<?php
namespace ide\editors\sprite;

use game\SpriteSpec;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\EventHandlerBehaviour;
use ide\misc\SimpleSingleCommand;
use ide\ui\FlowListViewDecorator;
use ide\utils\UiUtils;
use php\gui\event\UXEvent;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXComboBox;
use php\gui\UXComboBoxBase;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXNode;
use php\lib\Items;
use php\util\Flow;
use php\util\Regex;
use php\util\SharedStack;

/**
 * Class IdeAnimationSpritePane
 * @package ide\editors\sprite
 */
class IdeAnimationSpritePane
{
    use EventHandlerBehaviour;

    /**
     * @var SpriteSpec
     */
    protected $spec;

    /**
     * @var string
     */
    protected $animation;

    /**
     * @var FlowListViewDecorator
     */
    protected $framesPane;

    /**
     * @var UXNode
     */
    protected $ui;

    /**
     * @var UXComboBox
     */
    protected $animationList;

    /**
     * @var UXHBox
     */
    protected $actions;

    /**
     * @param SpriteSpec $spec
     * @param $animation
     */
    public function __construct(SpriteSpec $spec, $animation)
    {
        $this->spec = $spec;
        $this->animation = $animation;

        $this->makeUi();
    }

    protected function makeUi()
    {
        $list = new FlowListViewDecorator(new UXFlowPane());
        $list->setEmptyListText('Перетащите сюда кадры для создания анимации ...');
        $list->clear();

        $list->on('append', [$this, 'doAppend']);
        $list->on('remove', function (array $nodes) {
            $indexes = Flow::of($nodes)->map(function ($node) { return $node->userData; })->toArray();

            if ($this->getAnimation()) {
                $this->spec->removeFromAnimation($this->getAnimation(), $indexes);
            }

            $this->trigger('change', []);
        });

        $this->framesPane = $list;

        $list->getPane()->maxHeight = 9999;
        UXVBox::setVgrow($list->getPane(), 'ALWAYS');

        $this->actions = $actions = UiUtils::makeCommandPane([
            '-',
            'add' => $this->createAddCommand(),
            '-',
            'delete' => $this->createDeleteCommand(),
            'rename' => $this->createRenameCommand(),
            'copy' => $this->createCopyCommand(),
        ]);

        $actions->alignment = 'BOTTOM_LEFT';
        $actions->spacing = 10;

        $actions->children->insert(0, $this->makeAnimationListUi());

        $ui = new UXVBox([
            $actions,
            $list->getPane()
        ]);

        $ui->spacing = 10;
        $ui->maxWidth = 9999;

        return $this->ui = $ui;
    }

    public function update()
    {
        $selected = $this->animationList->selected;
        $this->animationList->items->clear();

        if ($this->spec->animations) {
            $this->animationList->items->addAll(Items::keys($this->spec->animations));
        } else {
            $this->animationList->items->add('');
        }

        if ($this->spec->animations[$selected]) {
            $this->animationList->selected = $selected;
        }

        if ($node = $this->actions->lookup('#delete')) {
            $node->enabled = $selected !== null;
        }
    }

    public function getUi()
    {
        return $this->ui;
    }

    protected function makeAnimationListUi()
    {
        $label = new UXLabel('Анимация:');
        $combo = new UXComboBox();
        $combo->width = 200;

        $combo->on('action', function (UXEvent $e) {
            $selected = $e->sender->selected;
            $this->showAnimation($selected);
        });

        $combo->onButtonRender(function (UXListCell $cell, $text) {
            if ($text) {
                $cell->graphic = ico('film16');
                $cell->text = $text;
            } else {
                $cell->text = 'пусто';
            }
        });

        $combo->onCellRender(function (UXListCell $cell, $text) {
            if ($text) {
                $cell->graphic = ico('film16');
                $cell->text = $text;
            }
        });

        $this->animationList = $combo;

        $box = new UXHBox([$label, $combo]);
        $box->alignment = 'BASELINE_LEFT';
        $box->spacing = 5;
        return $box;
    }

    public function showAnimation($name)
    {
        if ($this->framesPane) {
            $this->animationList->selected = $name;

            $enabled = $name !== '' && $name !== null;

            if (!$enabled) {
                $this->animationList->selectedIndex = -1;
            }

            if ($node = $this->actions->lookup('#delete')) {
                $node->enabled = $enabled;
            }

            if ($node = $this->actions->lookup('#rename')) {
                $node->enabled = $enabled;
            }

            if ($node = $this->actions->lookup('#copy')) {
                $node->enabled = $enabled;
            }

            $this->framesPane->clear();

            if ($indexes = $this->spec->animations[$name]) {
                foreach ($indexes as $index) {
                    $this->trigger('add', [$index, $this->framesPane]);
                }
            }
        }
    }

    public function doAppend($index, $ids = [])
    {
        $animation = $this->animationList->selected;

        $indexes = $this->spec->animations[$animation];

        if ($indexes === null) {
            $animation = $this->doCreateAnimation();
        }

        if (!$animation) {
            return;
        }

        if ($index == -1 || !$indexes) {
            $this->spec->animations[$animation] = Items::toList((array) $indexes, $ids);
        } else {
            $before = Flow::of($indexes)->limit($index)->toArray();
            $after = Flow::of($indexes)->skip($index)->toArray();

            $this->spec->animations[$animation] = Items::toList(
                $before,
                $ids,
                $after
            );
        }

        $this->update();
        $this->trigger('change', []);
    }

    public function doRenameAnimation()
    {
        $animation = $this->getAnimation();

        if ($animation) {
            retry:
            $newName = UXDialog::input("Новое название для анимации '$animation'", $animation);

            if ($newName !== null) {
                if ($this->checkAnimationName($newName, false)) {
                    $this->spec->animations[$newName] = $this->spec->animations[$animation];
                    unset($this->spec->animations[$animation]);

                    $this->showAnimation($newName);
                    $this->update();

                    $this->trigger('change', []);
                } else {
                    goto retry;
                }
            }
        }
    }

    public function doCloneAnimation()
    {
        $animation = $this->getAnimation();

        if ($animation) {
            retry:
            $newName = UXDialog::input("Новое название для склонированной анимации", $animation);

            if ($newName !== null) {
                if ($this->checkAnimationName($newName)) {
                    $this->spec->animations[$newName] = $this->spec->animations[$animation];

                    $this->showAnimation($newName);
                    $this->update();

                    $this->trigger('change', []);
                } else {
                    goto retry;
                }
            }
        }
    }

    public function doDeleteAnimation()
    {
        $animation = $this->getAnimation();

        $dialog = new MessageBoxForm("Вы точно хотите удалить анимацию '$animation'?", ['Да, удалить', 'Нет']);

        if ($dialog->showDialog() && $dialog->getResultIndex() == 0) {
            unset($this->spec->animations[$animation]);

            $this->showAnimation(Items::firstKey($this->spec->animations));
            $this->update();

            $this->trigger('change', []);
        }
    }

    public function checkAnimationName($name, $checkExists = true)
    {
        if ($checkExists && isset($this->spec->animations[$name])) {
            UXDialog::showAndWait("'$name' анимация уже существует", 'ERROR');
            return false;
        }

        if (Regex::match('^[a-zA-Z0-9_]+$', $name)) {
            return true;
        } else {
            UXDialog::showAndWait("'$name' неподходящее названия для анимации, используйте только английские буквы, цифры и знак подчеркивания", 'ERROR');
            return false;
        }
    }

    public function doCreateAnimation()
    {
        retry:
        $animation = UXDialog::input('Придумайте название для анимации');

        if ($animation) {
            if ($this->checkAnimationName($animation)) {
                $this->spec->animations[$animation] = [];
                Ide::toast('Анимация была успешно добавлена');
            } else {
                goto retry;
            }

            $this->update();
            $this->animationList->selected = $animation;

            $this->trigger('change', []);

            return $animation;
        }
    }

    protected function createAddCommand()
    {
        return SimpleSingleCommand::makeWithText('Добавить', 'icons/plus16.png', [$this, 'doCreateAnimation']);
    }

    public function getAnimation()
    {
        return $this->animationList->selected;
    }

    private function createDeleteCommand()
    {
        return SimpleSingleCommand::makeWithText('Удалить анимацию', 'icons/trash16.png', [$this, 'doDeleteAnimation']);
    }

    private function createRenameCommand()
    {
        return SimpleSingleCommand::makeWithText('Переименовать', 'icons/rename16.png', [$this, 'doRenameAnimation']);
    }

    private function createCopyCommand()
    {
        return SimpleSingleCommand::makeWithText('Клонировать', 'icons/clone16.png', [$this, 'doCloneAnimation']);
    }
}