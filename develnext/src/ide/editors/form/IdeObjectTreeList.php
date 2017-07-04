<?php
namespace ide\editors\form;

use ide\editors\common\ObjectListEditorButtonRender;
use ide\editors\common\ObjectListEditorCellRender;
use ide\editors\common\ObjectListEditorItem;
use ide\editors\menu\ContextMenu;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\misc\EventHandlerBehaviour;
use php\gui\event\UXEvent;
use php\gui\layout\UXHBox;
use php\gui\UXButton;
use php\gui\UXComboBox;

/**
 * Class IdeObjectTreeList
 * @package ide\editors\form
 */
class IdeObjectTreeList
{
    use EventHandlerBehaviour;

    /**
     * @var UXComboBox
     */
    protected $ui;

    /**
     * @var ObjectListEditorItem
     */
    protected $emptyItem;

    /**
     * @var callable
     */
    protected $traverseFunc;

    /**
     * @var int
     */
    protected $levelOffset = 0;

    /**
     * @var ContextMenu
     */
    protected $contextMenu;

    /**
     * IdeObjectTreeList constructor.
     * @param ContextMenu $contextMenu
     */
    public function __construct(ContextMenu $contextMenu = null)
    {
        $this->contextMenu = $contextMenu;
    }

    /**
     * @param callable $traverseFunc
     */
    public function setTraverseFunc(callable $traverseFunc)
    {
        $this->traverseFunc = $traverseFunc;
    }

    /**
     * @param ObjectListEditorItem $emptyItem
     */
    public function setEmptyItem(ObjectListEditorItem $emptyItem)
    {
        $this->emptyItem = $emptyItem;
    }

    /**
     * @param int $levelOffset
     */
    public function setLevelOffset($levelOffset)
    {
        $this->levelOffset = $levelOffset;
    }

    public function setSelected($targetId)
    {
        $this->lockHandles();
        if ($targetId) {
            /** @var ObjectListEditorItem $item */
            foreach ($this->ui->items as $i => $item) {
                if ($item->value && $item->value == $targetId) {
                    $this->ui->selectedIndex = $i;
                    $this->unlockHandles();
                    return;
                }
            }
        }

        $this->ui->selectedIndex = $this->emptyItem ? 0 : -1;
        $this->unlockHandles();
    }

    public function update($selectedTargetId = null)
    {
        if (!$selectedTargetId) {
            $selectedTargetId = $this->ui->selected ? $this->ui->selected->value : null;
        }

        $this->lockHandles();

        $this->ui->items->clear();

        if ($this->emptyItem) {
            $this->ui->items->add($this->emptyItem);
        }

        if ($this->traverseFunc) {
            $func = $this->traverseFunc;

            $selected = null;

            $func(function ($target, $targetId, AbstractFormElement $element = null, $level) use (&$selected, $selectedTargetId) {
                if ($targetId) {
                    $item = new ObjectListEditorItem(
                        $targetId,
                        $element ? Ide::get()->getImage($element->getIcon()) : null,
                        $targetId,
                        $level + $this->levelOffset
                    );

                    if ($element) {
                        $item->hint = $element->getName();
                    }

                    if ($selectedTargetId && $selectedTargetId == $targetId) {
                        $selected = $item;
                    }

                    $this->ui->items->add($item);
                }
            });

            $this->ui->selected = $selected;

            if (!$selected) {
                $this->ui->selectedIndex = $this->emptyItem ? 0 : -1;
            }
        }

        $this->unlockHandles();
    }

    public function makeUi()
    {
        $ui = new UXComboBox();

        $ui->on('action', function (UXEvent $event) {
            $this->trigger('change', [$event->sender->selected ? $event->sender->selected->value : null]);
        });

        $handler = new ObjectListEditorCellRender();
        $handler->hideHint();

        $ui->onCellRender($handler);
        $ui->onButtonRender(new ObjectListEditorButtonRender());

        $this->ui = $ui;

        if ($this->contextMenu) {
            $btn = new UXButton('', ico('menu16'));
            $btn->tooltipText = 'Меню выбранного объекта';
            $btn->maxHeight = 999;

            $btn->on('action', function () use ($btn) {
                $this->contextMenu->show($btn);
            });

            UXHBox::setHgrow($ui, 'ALWAYS');
            $ui->maxWidth = 999;
            return new UXHBox([$btn, $ui], 1);
        } else {
            return $ui;
        }
    }
}