<?php
namespace ide\editors\form;

use develnext\lexer\token\ArgumentStmtToken;
use develnext\lexer\token\MethodStmtToken;
use ide\action\ActionEditor;
use ide\autocomplete\AutoCompleteRegion;
use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\editors\menu\ContextMenu;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\event\AbstractEventKind;
use ide\formats\form\SourceEventManager;
use ide\forms\ActionConstructorForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\Logger;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use php\desktop\Mouse;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXMenu;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXScreen;
use php\gui\UXTab;
use php\lang\IllegalStateException;
use php\lib\Items;
use php\lib\Str;
use timer\AccurateTimer;

class IdeEventListPane
{
    use EventHandlerBehaviour;

    /**
     * @var SourceEventManager
     */
    protected $manager;

    /**
     * @var UXNode
     */
    protected $ui;

    /**
     * @var UXListView
     */
    protected $uiList;

    /**
     * @var ContextMenu
     */
    protected $contextMenu;

    /**
     * @var array
     */
    protected $eventTypes = [];

    /**
     * @var mixed
     */
    protected $targetId = '';

    /**
     * @var CodeEditor
     */
    protected $codeEditor;

    /**
     * @var AbstractEditor
     */
    protected $contextEditor;

    /**
     * @var ActionEditor
     */
    protected $actionEditor;

    /**
     * @var array
     */
    protected $context;


    /**
     * @var UXNode
     */
    protected $hintNode;

    /**
     * @var string
     */
    protected $hintNodeText;

    /**
     * IdeEventListPane constructor.
     * @param SourceEventManager $manager
     */
    public function __construct(SourceEventManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getHintNode()
    {
        return $this->hintNode;
    }

    /**
     * @param mixed $hintNode
     */
    public function setHintNode($hintNode)
    {
        $this->hintNode = $hintNode;
        $this->hintNodeText = $hintNode->text;
    }

    /**
     * @param array $eventTypes
     */
    public function setEventTypes($eventTypes)
    {
        $this->eventTypes = $eventTypes;
    }

    public function makeUi()
    {
        $this->makeContextMenu();
        return $this->ui = $this->makeEventTypePane();
    }

    /**
     * @return UXNode
     */
    public function getUi()
    {
        return $this->ui;
    }

    /**
     * @return array
     */
    public function getEventTypes()
    {
        return $this->eventTypes;
    }

    /**
     * @return mixed
     */
    public function getTargetId()
    {
        return $this->targetId;
    }

    /**
     * @return CodeEditor
     */
    public function getCodeEditor()
    {
        return $this->codeEditor;
    }

    /**
     * @param CodeEditor $codeEditor
     */
    public function setCodeEditor(CodeEditor $codeEditor)
    {
        $this->codeEditor = $codeEditor;

        $codeEditor->on('update', function () {
            $this->codeEditor->save();
            $this->manager->load();

            $this->update($this->targetId);
        });
    }

    /**
     * @return ActionEditor
     */
    public function getActionEditor()
    {
        return $this->actionEditor;
    }

    /**
     * @param ActionEditor $actionEditor
     */
    public function setActionEditor(ActionEditor $actionEditor)
    {
        $this->actionEditor = $actionEditor;
    }

    /**
     * @return AbstractEditor
     */
    public function getContextEditor()
    {
        return $this->contextEditor;
    }

    /**
     * @param AbstractEditor $contextEditor
     */
    public function setContextEditor($contextEditor)
    {
        $this->contextEditor = $contextEditor;
    }

    protected function makeEventContextMenu(UXListView $list)
    {
        $menu = new ContextMenu();

        $menu->addCommand(AbstractCommand::makeWithText('Открыть в конструкторе', 'icons/wizard16.png', function () use ($list) {
            $selected = Items::first($list->selectedItems);

            if ($selected) {
                $this->jumpToLine($selected['eventCode']);
                $this->openInConstructor($selected['eventCode']);

                $this->trigger('edit', [$selected['eventCode'], 'constructor']);
            }
        }));

        $menu->addSeparator();

        $menu->addCommand(AbstractCommand::makeWithText('Открыть в php-редакторе', 'icons/phpFile16.png', function () use ($list) {
            $selected = Items::first($list->selectedItems);

            if ($selected) {
                $this->jumpToLine($selected['eventCode']);

                $this->trigger('edit', [$selected['eventCode'], 'php']);
            }
        }));

        return $menu;
    }

    public function jumpToLine($eventCode)
    {
        if ($this->codeEditor) {
            $bind = $this->manager->findBind($this->targetId, $eventCode);

            if ($bind) {
                $this->codeEditor->jumpToLine($bind['beginLine'], $bind['beginPosition']);
                $this->codeEditor->jumpToLineSpaceOffset($bind['beginLine']);
            }
        }
    }

    public function getDefaultEventEditor($request = true)
    {
        $editorType = Ide::get()->getUserConfigValue(CodeEditor::class . '.editorOnDoubleClick');

        if ($request && !$editorType) {
            $buttons = ['constructor' => 'Конструктор', 'php' => 'PHP редактор'];

            $dialog = new MessageBoxForm('Какой использовать редактор для редактирования событий?', $buttons, $this->ui);

            UXApplication::runLater(function () use ($dialog) {
                $dialog->toast('Используйте "Конструктор" если вы новичок!');
            });

            if ($dialog->showDialogWithFlag()) {
                $editorType = $dialog->getResult();

                if ($dialog->isChecked()) {
                    Ide::get()->setUserConfigValue(CodeEditor::class . '.editorOnDoubleClick', $editorType);
                }
            }
        }

        return $editorType;
    }

    public function openEventSource($eventCode, $editorType = null)
    {
        Logger::info("Start opening even source: eventCode = $eventCode, editorType = $editorType");

        if (!$editorType) {
            $editorType = $this->getDefaultEventEditor();
            Logger::info("... default event editor = $editorType");
        }

        switch ($editorType) {
            case 'php':
                $this->jumpToLine($eventCode);
                break;
            case 'constructor':
                $this->openInConstructor($eventCode);
                break;
        }

        return $editorType;
    }

    protected function openInConstructor($eventCode)
    {
        if ($this->actionEditor) {
            $bind = $this->manager->findBind($this->targetId, $eventCode);

            if ($bind) {
                $eventType = null;

                $code = $eventCode;

                list($eventCode, $eventParam) = Str::split($eventCode, '-', 2);

                foreach ($this->eventTypes as $el) {
                    if (Str::equalsIgnoreCase($el['code'], $eventCode)) {
                        $eventType = $el;
                    }
                }

                $selectedClass = $bind['className'];
                $selectedMethod = $bind['methodName'];

                $actionConstructor = new ActionConstructorForm();
                $actionConstructor->setContext($this->context);

                $actionConstructor->getLiveCodeEditor()->getAutoComplete()->getComplete()->on(
                    'addFunctionArgument',
                    function ($type, ArgumentStmtToken $argument, $index, MethodStmtToken $method, AutoCompleteRegion $region) {
                        return $this->codeEditor
                            ->getAutoComplete()
                            ->getComplete()
                            ->trigger('addFunctionArgument', [$type, $argument, $index, $method, $region]);
                    }
                );

                $actionConstructor->setLiveCode($this->codeEditor->getValue(), $bind['beginLine'], $bind['beginPosition']);
                    //$this->manager->getCodeOfMethod($selectedClass, $selectedMethod));

                if ($eventType) {
                    $actionConstructor->title = 'Событие - ' . $eventType['name'];

                    if ($eventParam) {
                        $actionConstructor->title .= ' (' . $eventType['kind']->findParamName($eventParam) . ')';
                    }

                    if ($this->targetId) {
                        $actionConstructor->title .= ' - id = "' . $this->targetId . '"';
                    }
                } else {
                    $actionConstructor->title = "Метод - $selectedMethod()";
                }

                if ($this->contextEditor) {
                    $actionConstructor->setContextEditor($this->contextEditor);
                }

                $actionConstructor->showAndWait($this->actionEditor, $selectedClass, $selectedMethod);

                $this->codeEditor->resetSettings();

                if ($actionConstructor->getResult()) {
                    if ($actionConstructor->getLiveCode() != $this->codeEditor->getValue()) {
                        $this->codeEditor->setValue($actionConstructor->getLiveCode());
                        $this->codeEditor->save();

                        $this->manager->load();
                    }

                    $this->update($this->targetId);

                    //$this->codeEditor->load(false);
                    $this->jumpToLine($eventCode);
                }

            }
        }
    }

    protected function makeContextMenu()
    {
        $this->contextMenu = new ContextMenu();
        $this->contextMenu->add(new IdeEventListPaneAddCommand($this));
        $this->contextMenu->add(new IdeEventListPaneChangeCommand($this));
        $this->contextMenu->add(new IdeEventListPaneOpenInConstructorCommand($this));
        $this->contextMenu->add(new IdeEventListPaneOpenInPhpEditorCommand($this));
        $this->contextMenu->add(new IdeEventListPaneDeleteCommand($this));
    }

    protected function makeContextMenuForEdit(callable $onAction, $withEdit = false)
    {
        $menu = new UXContextMenu();
        $prevKind = null;

        foreach ($this->eventTypes as $type) {
            $menuItem = new UXMenuItem($type['name'], Ide::get()->getImage($type['icon']));

            /** @var AbstractEventKind $kind */
            $kind = $type['kind'];

            $variants = $kind->getParamVariants($this->contextEditor);

            if (!$variants) {
                if ($bind = $this->manager->findBind($this->targetId, $type['code'])) {
                    if ($withEdit) {
                        $menuItem->style = '-fx-font-weight: bold;';
                        $menuItem->enabled = true;
                    } else {
                        $menuItem->style = '';
                        $menuItem->enabled = false;
                    }
                }

                $menuItem->on('action', function () use ($type, $onAction, $bind) {
                    $onAction($type, $type['code'], $bind);
                });
            } else {
                $menuItem = new UXMenu($menuItem->text, $menuItem->graphic);
            }

            if ($prevKind && get_class($prevKind) != get_class($type['kind'])) {
                $menu->items->add(UXMenuItem::createSeparator());
            }

            $menu->items->add($menuItem);

            if ($type['separator']) {
                $menu->items->add(UXMenuItem::createSeparator());
            }

            if ($variants) {
                $appendVariants = function ($variants, UXMenu $menuItem) use ($type, &$appendVariants, $onAction, $withEdit) {
                    foreach ($variants as $name => $param) {
                        if ($param === '-') {
                            $menuItem->items->add(UXMenuItem::createSeparator());
                            continue;
                        }

                        if (is_array($param)) {
                            $subItem = new UXMenu($name);
                            $menuItem->items->add($subItem);

                            $appendVariants($param, $subItem);
                            continue;
                        }

                        $code = $type['code'];

                        if ($param) {
                            $code .= "-$param";
                        }

                        $item = new UXMenuItem($name);

                        if ($param === false) {
                            $item->disable = true;
                        }

                        if ($bind = $this->manager->findBind($this->targetId, $code)) {
                            if ($withEdit) {
                                $item->style = '-fx-font-weight: bold;';
                                $item->enabled = true;
                            } else {
                                $item->style = '';
                                $item->enabled = false;
                            }

                            $item->userData = $bind;
                        }


                        $item->on('action', function () use ($type, $code, $onAction, $bind) {
                            $onAction($type, $code, $bind);
                        });

                        $menuItem->items->add($item);
                    }
                };

                $appendVariants($variants, $menuItem);
            }

            $prevKind = $type['kind'];
        }

        return $menu;
    }


    protected function makeEventTypePane()
    {
        $addButton = new UXButton("Добавить событие");
        $addButton->height = 30;
        $addButton->maxWidth = 10000;
        $addButton->style = '-fx-font-weight: bold;';
        $addButton->graphic = Ide::get()->getImage('icons/plus16.png');

        $addButton->on('action', function (UXEvent $event) {
            $this->doAddEvent($event);
        });

        $changeButton = new UXButton();
        $changeButton->size = [25, 25];
        $changeButton->graphic = Ide::get()->getImage('icons/exchange16.png');
        $changeButton->tooltipText = 'Поменять событие';

        $changeButton->on('action', function (UXEvent $event) {
            $this->doChangeEvent($event);
        });

        $deleteButton = new UXButton();
        $deleteButton->size = [25, 25];
        $deleteButton->graphic = Ide::get()->getImage('icons/delete16.png');

        $editButton = new UXButton("Редактировать");
        $editButton->graphic = Ide::get()->getImage('icons/edit16.png');
        $editButton->height = 25;
        $editButton->maxWidth = 10000;
        UXHBox::setHgrow($editButton, 'ALWAYS');

        $otherButtons = new UXHBox([$deleteButton, $changeButton, $editButton]);
        $otherButtons->spacing = 3;
        $otherButtons->height = 25;
        $otherButtons->maxWidth = 10000;

        $actions = new UXVBox([$addButton, $otherButtons]);
        $actions->fillWidth = true;
        $actions->spacing = 4;
        $actions->padding = 4;
        $actions->leftAnchor = 0;
        $actions->rightAnchor = 0;

        $pane = new UXAnchorPane();

        $list = new UXListView();
        UXAnchorPane::setAnchor($list, 2);
        $list->topAnchor = 68;
        $list->id = 'list';

        $deleteButton->on('action', function () use ($list) {
            $this->doDeleteEvent();
        });

        $editButton->on('action', function () use ($list) {
            $selected = Items::first($list->selectedItems);

            if (!$selected && $list->items->count()) {
                $list->selectedIndexes = [0];
                $selected = $list->items[0];
            }

            if ($selected) {
                $this->openInConstructor($selected['eventCode']);

                $this->trigger('edit', [$selected['eventCode'], $this->getDefaultEventEditor()]);
            }
        });

        $list->on('click', function (UXMouseEvent $e) use ($list) {
            if ($e->clickCount > 1) {
                $selected = Items::first($list->selectedItems);

                if ($selected) {
                    $editor = null;

                    if ($selected['info']['actionCount']) {
                        $editor = 'constructor';
                    }

                    $this->openEventSource($selected['eventCode'], $editor);

                    $this->trigger('edit', [$selected['eventCode'], $editor ?: $this->getDefaultEventEditor()]);
                }
            }
        });

        $eventContextMenu = $this->makeEventContextMenu($list);

        $this->contextMenu->linkTo($list);
        $list->setCellFactory(function (UXListCell $cell, $item) use ($list, $deleteButton, $eventContextMenu) {
            if ($item) {
                /** @var array $eventType */
                $eventType = $item['type'];
                $methodName = $item['info']['methodName'];

                $actionCount = $item['info']['actionCount'];
                $codeEmpty = $item['info']['codeEmpty'];

                $param = $item['paramName'];

                $cell->text = null;

                $constructorLink = new UXHyperlink('Изменить');
                $constructorLink->style = '-fx-text-fill: gray';

                $constructorLink->on('action', function ($event) use ($item, $deleteButton, $constructorLink, $eventContextMenu) {
                    foreach ($this->uiList->items as $i => $el) {
                        if ($el === $item) {
                            UXApplication::runLater(function () use ($i) {
                                $this->uiList->selectedIndex = $i;
                                $this->uiList->focusedIndex = $i;
                            });

                            break;
                        }
                    }

                    $eventContextMenu->getRoot()->showByNode($constructorLink, 0, 18);
                });

                $phpLink = new UXHyperlink('php');

                $name = new UXLabel($eventType['name']);
                $name->font = $name->font->withBold();
                if ($codeEmpty && !$actionCount) {
                    $name->textColor = 'gray';
                }

                $nameLabel = new UXHBox([$name]);
                $nameLabel->spacing = 4;

                if ($actionCount) {
                    $node = new UXLabel("+$actionCount");
                    $node->textColor = 'blue';
                    $node->font = $node->font->withSize(10)->withBold();
                    $nameLabel->add($node);
                }

                $methodNameLabel = new UXLabel($methodName);
                $methodNameLabel->textColor = UXColor::of('gray');

                if ($param) {
                    $paramLabel = new UXLabel("($param)");
                    $paramLabel->style = '-fx-font-style: italic';
                    $paramLabel->textColor = UXColor::of('#2f6eb2');
                    $paramLabel->paddingRight = 3;

                    $line = new UXHBox([$paramLabel, $constructorLink]);
                } else {
                    $line = new UXHBox([$constructorLink]);
                }

                $namesBox = new UXVBox([$nameLabel, $line]);

                $icon = str::trim($eventType['icon']) ? Ide::get()->getImage($eventType['icon']) : null;

                if ($icon) {
                    $box = new UXHBox([$icon, $namesBox]);
                    $box->spacing = 8;
                    $box->alignment = 'CENTER_LEFT';
                } else {
                    $box = $namesBox;
                }

                $box->padding = [3, 3];

                $cell->graphic = $box;
            }
        });

        $pane->add($actions);
        $pane->add($list);

        $this->uiList = $list;

        return $pane;
    }

    public function getSelectedCode()
    {
        if (!$this->ui) throw new IllegalStateException();

        $selected = Items::first($this->uiList->selectedItems);

        return $selected ? $selected['eventCode'] : null;
    }

    public function setSelectedCode($eventCode)
    {
        if (!$this->ui) throw new IllegalStateException();

        foreach ($this->uiList->items as $i => $item) {
            if ($item['eventCode'] == $eventCode) {
                $this->uiList->selectedIndexes = [$i];
                break;
            }
        }
    }

    public function update($targetId)
    {
        if (!$this->ui) {
            throw new IllegalStateException();
        }

        $this->targetId = $targetId;

        $binds = $this->manager->findBinds($this->targetId);

        $selectedCode = $this->getSelectedCode();

        $this->uiList->items->clear();

        $count = 0;

        foreach ($binds as $code => $info) {
            list($code, $param) = Str::split($code, '-');

            if ($eventType = $this->eventTypes[$code]) {
                $count++;

                $info['actionCount'] = sizeof($this->actionEditor->findMethod($info['className'], $info['methodName']));
                $info['codeEmpty'] = !str::trim($this->manager->getCodeOfMethod($info['className'], $info['methodName']));

                $this->uiList->items->add([
                    'type' => $eventType,
                    'info' => $info,
                    'param' => $param,
                    'paramName' => $eventType['kind']->findParamName($param, $this->contextEditor),
                    'eventCode' => $param ? "$eventType[code]-$param" : $eventType['code'],
                ]);
            }
        }

        if ($this->hintNode) {
            if ($this->hintNode instanceof UXTab || $this->hintNode instanceof UXLabeled) {
                if ($count) {
                    $this->hintNode->text = "";
                    $countLabel = new UXLabel("+{$count}");
                    $countLabel->textColor = 'blue';
                    $countLabel->font = $countLabel->font->withSize(10)->withBold();

                    $this->hintNode->graphic = new UXHBox([new UXLabel($this->hintNodeText), $countLabel]);
                    $this->hintNode->graphic->spacing = 2;
                } else {
                    $this->hintNode->graphic = new UXLabel($this->hintNodeText);
                    $this->hintNode->graphic->textColor = 'gray';
                    $this->hintNode->text = "";
                }
            }
        }

        $this->setSelectedCode($selectedCode);
    }

    public function doDeleteEvent()
    {
        $selected = Items::first($this->uiList->selectedItems);

        if ($selected) {
            if (MessageBoxForm::confirmDelete('событие ' . $selected['type']['name'], $this->ui)) {
                if ($bind = $this->manager->removeBind($this->targetId, $selected['eventCode'])) {
                    if ($this->actionEditor) {
                        $this->actionEditor->removeMethod($bind['className'], $bind['methodName']);
                    }

                    AccurateTimer::executeAfter(100, function () use ($selected, $bind) {
                        if ($this->codeEditor) {
                            $this->codeEditor->loadContentToArea(false);
                            $this->codeEditor->doChange(true);
                        }

                        $this->manager->load();

                        $this->update($this->targetId);

                        $this->trigger('remove', [$selected['eventCode'], $bind]);
                    });
                }

                $this->update($this->targetId);
            }
        }
    }

    public function doChangeEvent(UXEvent $event = null)
    {
        $menu = $this->makeContextMenuForEdit(function (array $type, $code) {
            $bind = $this->manager->changeBind($this->targetId, $this->getSelectedCode(), $code);

            if ($this->actionEditor && $bind['methodName'] != $bind['newMethodName']) {
                $this->actionEditor->renameMethod($bind['className'], $bind['methodName'], $bind['newMethodName']);
            }

            uiLater(function () use ($code) {
                if ($this->codeEditor) {
                    $this->codeEditor->loadContentToArea(false);
                    $this->codeEditor->doChange(true);
                }

                $this->manager->load();
                $this->update($this->targetId);
                $this->setSelectedCode($code);
            });
        });

        if ($event) {
            /** @var UXNode $target */
            $target = $event->sender;
            $menu->showByNode($target, $target->boundsInParent['width'], 0);
        } else {
            $menu->show($this->ui->form, Mouse::x(), Mouse::y());
        }
    }

    /**
     * @param bool $editable
     * @param UXNode|null $target
     */
    public function showEventMenu($editable = false, UXNode $target = null)
    {
        $menu = $this->makeContextMenuForEdit(function (array $type, $code, $bind) {
            if ($bind) {
                $editor = $this->openEventSource($code);

                $this->trigger('edit', [$code, $editor ?: $this->getDefaultEventEditor()]);
            } else {
                $this->manager->addBind($this->targetId, $code, $type['kind']);

                uiLater(function () use ($code, $type) {
                    if ($this->codeEditor) {
                        $this->codeEditor->loadContentToArea(false);
                        $this->codeEditor->doChange(true);
                    }

                    $this->manager->load();

                    $this->update($this->targetId);

                    $this->setSelectedCode($code);
                    $editor = $this->openEventSource($code);

                    $this->trigger('add', [$code, $editor]);
                });
            }
        }, $editable);

        if ($target) {
            $offsetX = $target->boundsInParent['width'] / 4;

            $menu->showByNode($target, $offsetX, $target->boundsInParent['height']);
        } else {
            $menu->show($this->ui->form, Mouse::x(), Mouse::y());
        }
    }

    public function doAddEvent(UXEvent $event = null)
    {
        $this->showEventMenu(false, $event ? $event->sender : null);
    }
}

// ---------------------------------------------------------------------------------------------------------------------

class IdeEventListPaneOpenInConstructorCommand extends AbstractMenuCommand
{
    /** @var IdeEventListPane */
    protected $eventPane;

    /**
     * IdeEventListPaneDeleteCommand constructor.
     * @param IdeEventListPane $eventPane
     */
    public function __construct(IdeEventListPane $eventPane)
    {
        $this->eventPane = $eventPane;
    }

    public function getName()
    {
        return 'Открыть в конструкторе';
    }

    public function getIcon()
    {
        return 'icons/wizard16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->eventPane->openEventSource($this->eventPane->getSelectedCode(), 'constructor');
        $this->eventPane->trigger('edit', [$this->eventPane->getSelectedCode(), 'constructor']);
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        $item->disable = !$this->eventPane->getSelectedCode();
    }
}


class IdeEventListPaneOpenInPhpEditorCommand extends AbstractMenuCommand
{
    /** @var IdeEventListPane */
    protected $eventPane;

    /**
     * IdeEventListPaneDeleteCommand constructor.
     * @param IdeEventListPane $eventPane
     */
    public function __construct(IdeEventListPane $eventPane)
    {
        $this->eventPane = $eventPane;
    }

    public function getName()
    {
        return 'Открыть в php-редакторе';
    }

    public function getIcon()
    {
        return 'icons/phpFile16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->eventPane->jumpToLine($this->eventPane->getSelectedCode());
        $this->eventPane->trigger('edit', [$this->eventPane->getSelectedCode(), 'php']);
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        $item->disable = !$this->eventPane->getSelectedCode();
    }

    public function withSeparator()
    {
        return true;
    }
}

class IdeEventListPaneAddCommand extends AbstractMenuCommand
{
    /** @var IdeEventListPane */
    protected $eventPane;

    /**
     * IdeEventListPaneDeleteCommand constructor.
     * @param IdeEventListPane $eventPane
     */
    public function __construct(IdeEventListPane $eventPane)
    {
        $this->eventPane = $eventPane;
    }

    public function getName()
    {
        return 'Добавить событие';
    }

    public function getIcon()
    {
        return 'icons/plus16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->eventPane->doAddEvent();
    }
}

class IdeEventListPaneChangeCommand extends AbstractMenuCommand
{
    /** @var IdeEventListPane */
    protected $eventPane;

    /**
     * IdeEventListPaneDeleteCommand constructor.
     * @param IdeEventListPane $eventPane
     */
    public function __construct(IdeEventListPane $eventPane)
    {
        $this->eventPane = $eventPane;
    }

    public function getName()
    {
        return 'Поменять событие';
    }

    public function getIcon()
    {
        return 'icons/exchange16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->eventPane->doChangeEvent();
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        $item->disable = !$this->eventPane->getSelectedCode();
    }

    public function withSeparator()
    {
        return true;
    }
}

class IdeEventListPaneDeleteCommand extends AbstractMenuCommand
{
    /** @var IdeEventListPane */
    protected $eventPane;

    /**
     * IdeEventListPaneDeleteCommand constructor.
     * @param IdeEventListPane $eventPane
     */
    public function __construct(IdeEventListPane $eventPane)
    {
        $this->eventPane = $eventPane;
    }

    public function getName()
    {
        return 'Удалить';
    }

    public function getIcon()
    {
        return 'icons/delete16.png';
    }

    public function getAccelerator()
    {
        return 'delete';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->eventPane->doDeleteEvent();
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        $item->disable = !$this->eventPane->getSelectedCode();
    }
}