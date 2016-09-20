<?php
namespace ide\ui;

use ide\Ide;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\utils\Json;
use php\gui\event\UXDragEvent;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXScrollPane;
use php\gui\paint\UXColor;
use php\gui\shape\UXRectangle;
use php\gui\UXContextMenu;
use php\gui\UXForm;
use php\gui\UXLabel;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\lib\Items;
use php\lib\Str;

/**
 * Class FlowListView
 * @package ide\ui
 */
class FlowListViewDecorator implements \Countable
{
    use EventHandlerBehaviour;

    /**
     * @var UXFlowPane
     */
    protected $pane;

    /**
     * @var UXScrollPane
     */
    protected $scrollPane;

    /**
     * @var bool
     */
    protected $multipleSelection = true;

    /**
     * @var bool
     */
    protected $dragging = false;

    /**
     * @var string
     */
    protected $emptyListText;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var UXContextMenu
     */
    protected $menu;

    /**
     * @var bool
     */
    protected $isDragging = false;

    /**
     * @var UXForm
     */
    protected $selection;

    public function __construct(UXFlowPane $pane = null)
    {
        if ($pane == null) {
            $pane = new UXFlowPane();
        }

        $this->scrollPane = new UXScrollPane();
        $this->scrollPane->content = $pane;
        $this->scrollPane->fitToWidth = true;
        $this->scrollPane->fitToHeight = true;

        $this->pane = $pane;

        $pane->hgap = 10;
        $pane->vgap = 10;
        $pane->padding = 10;
        $pane->backgroundColor = UXColor::of('white');

        $pane->classes->add('flow-list-view');

        $pane->on('mouseDown', function (UXMouseEvent $e) {
            if ($e->button == 'SECONDARY' && $this->getSelectionNodes()) {
                $this->menu->show($e->sender->form, $e->screenX, $e->screenY);
            } else {
                $this->selection->title = '...';
                $this->selection->size = [1, 1];

                if ($this->isMultipleSelection()) {
                    $this->selection->data('drag', true);
                }

                $this->selection->data('dragX', $e->screenX);
                $this->selection->data('dragY', $e->screenY);

                $this->selection->x = $e->screenX;
                $this->selection->y = $e->screenY;

                $this->clearSelections();
            }
        }, __CLASS__);

        $pane->on('dragOver', [$this, 'doDragOver'], __CLASS__);
        $pane->on('dragDone', function (UXDragEvent $e) { $e->consume(); });
        $pane->on('dragDrop', [$this, 'doDragDrop'], __CLASS__);

        $this->selection = new UXForm();
        $this->selection->style = 'TRANSPARENT';
        $this->selection->transparent = true;

        $this->selection->layout->backgroundColor = UXColor::of('black');
        $this->selection->layout->opacity = 0.5;

        $pane->on('mouseDrag', function (UXMouseEvent $e) {
            if ($this->selection->data('drag')) {
                $dragX = $this->selection->data('dragX');
                $dragY = $this->selection->data('dragY');

                $width = ($e->screenX - $dragX);
                $height = ($e->screenY - $dragY);

                if (abs($width) > 2 && abs($height) > 2) {
                    $this->selection->size = [1, 1];

                    $this->selection->width = abs($width);
                    $this->selection->height = abs($height);

                    $this->selection->x = $dragX;
                    $this->selection->y = $dragY;

                    if ($width < 0) {
                        $this->selection->x += $width;
                    }

                    if ($height < 0) {
                        $this->selection->y += $height;
                    }

                    $this->selection->show();
                }
            }
        }, __CLASS__);

        $pane->on('mouseUp', function (UXMouseEvent $e) {
            if (!$this->selection->visible) {
                return;
            }

            if (!$e->controlDown) {
                $this->clearSelections();
            }

            list($x, $y) = $this->pane->screenToLocal($this->selection->x, $this->selection->y);
            list($w, $h) = $this->selection->size;

            $selectedNodes = [];

            foreach ($this->pane->children as $node) {
                $nx = $node->x;
                $ny = $node->y;

                $nw = $node->width;
                $nh = $node->height;

                $nCenter = [$nx + round($nw / 2), $ny + round($nh / 2)];
                $center = [$x + round($w / 2), $y + round($h / 2)];

                $_w = abs($center[0] - $nCenter[0]);
                $_h = abs($center[1] - $nCenter[1]);

                $checkW = $_w < ($w / 2 + $nw / 2);
                $checkH = $_h < ($h / 2 + $nh / 2);

                if ($checkW && $checkH) {
                    $selectedNodes[] = $node;
                }
            }

            $this->setSelectionNodes($selectedNodes);

            $this->selection->hide();
            $this->selection->size = [1, 1];

            $this->selection->data('drag', false);
        }, __CLASS__);

        $this->id = Str::random();

        $this->initMenu();
    }

    public function replaceInParent()
    {
        if ($parent = $this->pane->parent) {
            $newPane = $this->getPane();

            $newPane->size = $this->pane->size;
            $newPane->position = $this->pane->position;
            $newPane->anchors = $this->pane->anchors;

            $parent->children->replace($this->pane, $newPane);
        }
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function initMenu()
    {
        $this->menu = new UXContextMenu();

        $deleteItem = new UXMenuItem('Удалить', ico('delete16'));
        $deleteItem->on('action', function () {
            $this->removeBySelections();
        });

        $this->menu->items->add($deleteItem);
    }

    public function addMenuCommand(AbstractCommand $command)
    {
        $last = $this->menu->items[$this->menu->items->count - 1];

        $this->menu->items->removeByIndex($this->menu->items->count - 1);

        if ($command->withBeforeSeparator()) {
            $this->menu->items->add(UXMenuItem::createSeparator());
        }

        $this->menu->items->add($command->makeMenuItem());

        if ($command->withAfterSeparator()) {
            $this->menu->items->add(UXMenuItem::createSeparator());
        }

        $this->menu->items->add($last);
    }

    /**
     * @return \php\gui\UXList
     */
    public function getChildren()
    {
        return $this->pane->children;
    }

    /**
     * @return UXScrollPane
     */
    public function getPane()
    {
        return $this->scrollPane;
    }

    public function addSelectionNode(UXNode $node)
    {
        if (!$node->classes->has('selected')) {
            $node->classes->add('selected');

            $this->trigger('select', [$this->getSelectionNodes()]);
        }
    }

    public function toggleSelectionNode(UXNode $node)
    {
        if (!$node->classes->has('selected')) {
            $node->classes->add('selected');
        } else {
            $node->classes->remove('selected');
        }

        $this->trigger('select', [$this->getSelectionNodes()]);
    }

    public function setSelectionNodes(array $nodes)
    {
        $this->clearSelections();

        foreach ($nodes as $node) {
            $node->classes->add('selected');
        }

        $this->trigger('select', [$nodes]);
    }

    public function getSelectionIndexes()
    {
        $nodes = [];

        /** @var UXNode $node */
        foreach ($this->pane->children as $i => $node) {
            if ($node->classes->has('selected')) {
                $nodes[] = $i;
            }
        }

        return $nodes;
    }

    /**
     * @return UXNode[]
     */
    public function getSelectionNodes()
    {
        $nodes = [];

        /** @var UXNode $node */
        foreach ($this->pane->children as $node) {
            if ($node->classes->has('selected')) {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    /**
     * @return UXNode
     */
    public function getSelectionNode()
    {
        return Items::first($this->getSelectionNodes());
    }

    public function clearSelections()
    {
        foreach ($this->pane->children as $node) {
            $node->classes->remove('selected');
        }

        $this->trigger('select', [[]]);
    }

    /**
     * @return string
     */
    public function getEmptyListText()
    {
        return $this->emptyListText;
    }

    /**
     * @param string $emptyListText
     */
    public function setEmptyListText($emptyListText)
    {
        $this->emptyListText = $emptyListText;
    }

    /**
     * @return boolean
     */
    public function isMultipleSelection()
    {
        return $this->multipleSelection;
    }

    /**
     * @param boolean $multipleSelection
     */
    public function setMultipleSelection($multipleSelection)
    {
        $this->multipleSelection = $multipleSelection;
        $this->clearSelections();
    }

    public function add(UXNode $node)
    {
        if ($this->pane->children->count == 1) {
            $item = Items::first($this->pane->children);

            if ($item->classes->has('empty-list-text')) {
                $this->pane->children->clear();
            }
        }

        $node->classes->add('list-cell');
        $this->pane->add($node);

        $node->on('mouseDown', function (UXMouseEvent $e) {
            if (!$e->controlDown && !($e->button == 'SECONDARY' && $e->sender->classes->has('selected'))) {
                $this->clearSelections();
            }

            if (!$this->isMultipleSelection()) {
                $this->clearSelections();
            }

            $e->consume();

            if ($e->button == 'SECONDARY') {
                $this->addSelectionNode($e->sender);

                $this->menu->show($e->sender->form, $e->screenX, $e->screenY);
            } else {
                $this->toggleSelectionNode($e->sender);
            }
        }, __CLASS__);


        $node->on('mouseEnter', function (UXEvent $e) {
            $e->sender->classes->add('hovered');
        }, __CLASS__);

        $node->on('mouseExit', function (UXEvent $e) {
            $e->sender->classes->remove('hovered');
        }, __CLASS__);

        $node->on('dragEnter', function (UXEvent $e) {
            $e->sender->classes->add('dragged');
        }, __CLASS__);

        $node->on('dragExit', function (UXEvent $e) {
            $e->sender->classes->remove('dragged');
        }, __CLASS__);

        $node->on('dragOver', [$this, 'doDragOver'], __CLASS__);
        $node->on('dragDrop', [$this, 'doDragDrop'], __CLASS__);
        $node->on('dragDone', function (UXDragEvent $e) {
            $e->sender->data('dragged', false);
        }, __CLASS__);

        $node->on('dragDetect', function (UXMouseEvent $e) {
            $e->sender->data('dragged', true);

            $dragboard = $e->sender->startDrag(['MOVE']);
            $dragboard->dragView = $e->sender->snapshot();

            $dragboard->dragViewOffsetX = $dragboard->dragView->width / 2;
            $dragboard->dragViewOffsetY = $dragboard->dragView->height / 2;

            $dragboard->string = Json::encode([
                'id'    => $this->id,
                'indexes' => $this->getSelectionIndexes()
            ]);

            $e->consume();
        }, __CLASS__);
    }

    public function clear()
    {
        $this->pane->children->clear();

        $object = new UXLabel($this->emptyListText);
        $object->classes->addAll(['empty-list-text', 'dn-list-hint']);

        $this->pane->children->add($object);
    }

    public function count()
    {
        return $this->pane->children->count();
    }

    public function removeBySelections()
    {
        $nodes = $this->getSelectionNodes();

        if ($this->trigger('beforeRemove', [$nodes])) {
            return;
        }

        foreach ($nodes as $node) {
            $this->remove($node, false);
        }

        $this->trigger('remove', [$nodes]);
    }

    public function remove(UXNode $node, $trigger = true)
    {
        if ($this->pane->children->has($node)) {
            $node->classes->remove('list-cell');
            $node->classes->remove('selected');

            $this->pane->children->remove($node);

            if ($trigger) {
                $this->trigger('remove', [[$node]]);
            }

            if (!$this->pane->children->count) {
                $object = new UXLabel($this->emptyListText);
                $object->classes->addAll(['empty-list-text', 'dn-list-hint']);

                $this->pane->children->add($object);
            }
        }
    }

    protected function doDragOver(UXDragEvent $e)
    {
        $data = Json::decode($e->dragboard->string);

        if ($data['id'] && $data['indexes']) {
            if ($this->pane === $e->sender) {
                if ($data['id'] === $this->id) {
                    return;
                }
            }

            $this->selection->hide();

            $e->acceptTransferModes(['MOVE']);
            $e->consume();
        }
    }

    protected function doDragDrop(UXDragEvent $e)
    {
        $dragboard = $e->dragboard;

        $value = Json::decode($dragboard->string);

        if ($value['id'] && $value['indexes']) {
            if ($value['id'] === $this->id) {
                $nodes = [];
                $draggedNode = null;

                foreach ($value['indexes'] as $i) {
                    $node = $this->pane->children[$i];
                    $nodes[] = $node;

                    if ($node->data('dragged')) {
                        $draggedNode = $node;

                        if ($draggedNode === $e->sender) {
                            return;
                        }
                    }

                    if ($node === $e->sender) {
                        return;
                    }
                }

                $removeAll = function () use ($nodes) {
                    foreach ($nodes as $node) {
                        $this->pane->remove($node);
                    }
                };

                if ($this->pane === $e->sender) {
                    /*if ($this->trigger('moving', [-1, $nodes]) !== false) {
                        $removeAll();

                        $this->pane->children->addAll($nodes);
                        $this->trigger('move', [-1, $nodes]);
                    } */
                } else {
                    $index = $this->pane->children->indexOf($e->sender);

                    if ($index === -1) {
                        return;
                    }

                    $indexOf = $this->pane->children->indexOf($draggedNode);
                    $needInc = false;

                    if ($indexOf < $index) {
                        $needInc = true;
                    }

                    if ($this->trigger('moving', [$index, $nodes]) !== false) {
                        $removeAll();
                        $index = $this->pane->children->indexOf($e->sender);

                        if ($needInc) {
                            $index++;
                        }

                        $this->pane->children->insertAll($index, $nodes);

                        uiLater(function () use ($index, $nodes) {
                            $this->trigger('move', [$index, $nodes]);
                        });
                    }
                }
            } else {
                $index = $this->pane->children->indexOf($e->sender);
                $this->trigger('append', [$index, $value['indexes']]);

            }

            $e->consume();
        }
    }
}