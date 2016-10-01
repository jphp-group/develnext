<?php
namespace ide\forms;

use Dialog;
use ide\action\AbstractActionType;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionEditor;
use ide\action\ActionScript;
use ide\action\types\SleepActionType;
use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\editors\common\CodeTextArea;
use ide\editors\FormEditor;
use ide\editors\menu\ContextMenu;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\marker\ArrowPointMarker;
use ide\marker\target\ActionTypeMarketTarget;
use ide\marker\target\CancelButtonMarkerTarget;
use ide\misc\AbstractCommand;
use ide\misc\EventHandlerBehaviour;
use ide\utils\PhpParser;
use php\format\ProcessorException;
use php\gui\designer\UXPhpCodeArea;
use php\gui\event\UXDragEvent;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\text\UXFont;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXClipboard;
use php\gui\UXComboBox;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXSplitPane;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\io\MemoryStream;
use php\lang\IllegalStateException;
use php\lib\arr;
use php\lib\Items;
use php\lib\Str;
use php\util\Flow;
use php\util\Regex;
use php\xml\XmlProcessor;
use script\TimerScript;

/**
 * Class ActionConstructorForm
 * @package ide\forms
 *
 * @property UXListView $list
 * @property UXTabPane $actionTypePane
 * @property UXAnchorPane $generatedCodeContent
 * @property UXAnchorPane $codeContent
 * @property UXTabPane $tabs
 * @property UXCheckbox $useDefaultCheckbox
 * @property UXSplitPane $constructorSplitPane
 * @property UXComboBox $actionTypePaneViewCombobox
 */
class ActionConstructorForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;
    //use EventHandlerBehaviour;

    /**
     * @var ActionEditor
     */
    protected $editor;

    /**
     * @var FormEditor
     */
    protected $contextEditor;

    /**
     * @var CodeEditor
     */
    protected $liveCodeEditor;

    /** @var string */
    protected $class;

    /** @var string */
    protected $method;

    /**
     * @var array
     */
    protected $context;

    protected static $tabSelectedIndex = -1;
    protected static $globalTabSelectedIndex = 0;

    /**
     * @var bool
     */
    protected $onlyIcons = false;

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

    public function setDefaultEventEditor($editor)
    {
        Ide::get()->setUserConfigValue(CodeEditor::class . '.editorOnDoubleClick', $editor);
    }

    public function setOnlyIcons($value, $updateUi = true)
    {
        if ($value == $this->onlyIcons) {
            return;
        }

        $this->onlyIcons = $value;

        foreach ($this->actionTypePane->tabs as $tab) {
            $tab->content->content = $value ? $tab->content->data('fbox') : $tab->content->data('vbox');
        }

        Ide::get()->setUserConfigValue(get_class($this) . ".onlyIcons", $this->isOnlyIcons());

        if ($this->actionTypePaneViewCombobox && $updateUi) {
            $this->actionTypePaneViewCombobox->selectedIndex = $value ? 1 : 0;
        }
    }

    public function isOnlyIcons()
    {
        return $this->onlyIcons;
    }

    protected function init()
    {
        parent::init();

        $this->actionTypePaneViewCombobox->items->addAll([
            'Иконки + текст',
            'Только иконки'
        ]);
        $this->actionTypePaneViewCombobox->selectedIndex = 0;

        $this->actionTypePaneViewCombobox->on('action', function () {
            $this->setOnlyIcons($this->actionTypePaneViewCombobox->selectedIndex == 1, false);
        });

        $tabOne = $this->tabs->tabs[0]->text;
        $tabTwo = $this->tabs->tabs[1]->text;

        $this->tabs->tabs[0]->graphic = ico('wizard16');
        $this->tabs->tabs[1]->graphic = ico('phpFile16');

        $tabChange = function () {
            uiLater(function () {
                if ($this->tabs->selectedIndex == 1) {
                    $this->liveCodeEditor->requestFocus();
                }

                Ide::get()->setUserConfigValue(CodeEditor::class . '.tabIndex', $this->tabs->selectedIndex);
            });
        };

        $this->tabs->tabs[0]->on('change', $tabChange);
        $this->tabs->tabs[1]->on('change', $tabChange);

        waitAsync(1, function () use ($tabOne, $tabTwo) {

        });

        $this->list->items->addListener(function () use ($tabOne, $tabTwo) {
            $this->tabs->tabs[0]->text = $this->list->items->count ? "$tabOne *" : $tabOne;
        });

        UXApplication::runLater(function () {
            $this->useDefaultCheckbox->observer('selected')->addListener(function ($oldValue, $newValue) {
                if ($newValue) {
                    $this->setDefaultEventEditor('constructor');
                } else {
                    $this->setDefaultEventEditor('php');
                }
            });
        });

        $this->liveCodeEditor = $liveCodeEditor = new CodeEditor(null, 'php');
        $liveCodeEditor->registerDefaultCommands();

        $liveCodeView = $liveCodeEditor->makeUi();

        UXAnchorPane::setAnchor($liveCodeView, 2);
        $this->codeContent->add($liveCodeView);

        // -----

        $this->list->multipleSelection = true;

        $this->list->on('dragOver', [$this, 'listDragOver']);
        $this->list->on('dragDone', [$this, 'listDragDone']);
        $this->list->on('dragDrop', [$this, 'listDragDrop']);

        $this->list->setDraggableCellFactory([$this, 'listCellFactory'], [$this, 'listCellDragDone']);
        $this->hintLabel->mouseTransparent = true;

        $contextMenu = new ContextMenu();

        $contextMenu->addCommand(AbstractCommand::make('Редактировать', 'icons/edit16.png', function () {
            $this->actionEdit();
        }));

        $contextMenu->addSeparator();

        $contextMenu->addCommand(AbstractCommand::make('Вставить', 'icons/paste16.png', function () {
            $this->actionPaste();
        }, 'Ctrl+V'));

        $contextMenu->addCommand(AbstractCommand::make('Вырезать', 'icons/cut16.png', function () {
            $this->actionCopy();
            $this->actionDelete();
        }, 'Ctrl+X'));

        $contextMenu->addCommand(AbstractCommand::make('Копировать', 'icons/copy16.png', function () {
            $this->actionCopy();
        }, 'Ctrl+C'));

        $contextMenu->addCommand(AbstractCommand::make('Удалить', 'icons/delete16.png', function () {
            $this->actionDelete();
        }, 'Delete'));

        $this->list->contextMenu = $contextMenu->getRoot();
    }

    protected function listDragOver(UXDragEvent $e)
    {
        $e->acceptTransferModes(['MOVE']);
        $e->consume();
    }

    protected function listDragDone(UXDragEvent $e)
    {
        $e->consume();
    }

    protected function listDragDrop(UXDragEvent $e)
    {
        if ($this->list->items->count > 0) {
            return;
        }

        $dragboard = $e->dragboard;

        $value = $dragboard->string;

        if (class_exists($value)) {
            $actionType = new $value();

            if ($actionType instanceof AbstractSimpleActionType) {
                $dragboard->dragView = null;

                /** @var ActionConstructorForm $self */
                $self = $e->sender->scene->window->userData->self;

                UXApplication::runLater(function () use ($self, $actionType) {
                    $self->addAction($actionType);
                });

                $e->dropCompleted = true;
                $e->consume();
            }
        }
    }

    protected function listCellDragDone(UXDragEvent $e, UXListView $list, $index)
    {
        $dragboard = $e->dragboard;

        $value = $dragboard->string;

        if (class_exists($value)) {
            $actionType = new $value();
            $dragboard->dragView = null;

            if ($actionType instanceof AbstractSimpleActionType) {
                /** @var ActionConstructorForm $self */
                $self = $list->scene->window->userData->self;

                UXApplication::runLater(function () use ($self, $actionType, $index) {
                    $self->addAction($actionType, $index);
                    $this->editor->updateMethod($this->class, $this->method, Items::toArray($this->list->items));
                });
            } else {
                return false;
            }
        } elseif (Str::isNumber($value) && $value < $list->items->count && $value >= 0) {
            $indexes = $list->selectedIndexes;

            $dragged = [];

            foreach ($indexes as $i) {
                $dragged[] = $list->items[$i];
            }

            if ($index < (int) ($value)) {
                foreach ($dragged as $el) {
                    $list->items->remove($el);
                }

                foreach ($dragged as $el) {
                    $list->items->insert($index++, $el);
                }

                $index--;
            } else {
                $index++;

                foreach ($dragged as $el) {
                    $index--;
                    $list->items->remove($el);
                }

                foreach ($dragged as $el) {
                    $list->items->insert($index++, $el);
                }

                $index--;
            }

            UXApplication::runLater(function () use ($index) {
                $this->list->selectedIndex = $index;
            });
        }

        $this->editor->updateMethod($this->class, $this->method, Items::toArray($this->list->items));

        $this->updateList();

        $this->editor->calculateLevels(Items::toArray($this->list->items));
        $this->list->update();
    }

    protected function listCellFactory(UXListCell $cell, Action $action = null, $empty)
    {
        if ($action) {
            $titleName = new UXLabel($action->getTitle());
            $titleName->style = '-fx-font-weight: bold; -fx-text-fill: #383838;';

            if ($action->getDescription()) {
                $titleDescription = new UXLabel($action->getDescription());
                $titleDescription->style = '-fx-text-fill: gray;';
                $titleDescription->padding = 0;
            } else {
                $titleDescription = null;
            }

            $title = $action->getType()->makeUi($action, $titleName, $titleDescription);

            if ($title instanceof UXVBox || $title instanceof UXHBox) {
                $title->spacing = 0;

                if ($action->getType()->isDeprecated()) {
                    $title->opacity = 0.6;
                    $titleName->tooltipText = $titleDescription->tooltipText = 'Действие устарело, необходимо его заменить чем-то другим';
                }
            }

            $image = Ide::get()->getImage($action->getIcon());

            if (!$image) {
                $image = Ide::get()->getImage('icons/blocks16.png');
            }

            $line = new UXHBox([$image, $title]);
            $line->spacing = 10;
            $line->padding = 5;

            $line->alignment = 'CENTER_LEFT';

            $line->paddingLeft = 20 * $action->getLevel() + 5;

            if ($action->getType()->isCloseLevel() || $action->getType()->isAppendMultipleLevel()) {
                $line->paddingLeft -= 5;
            }

            $cell->text = null;
            $cell->graphic = $line;
        }
    }

    public function updateList()
    {
       $this->hintLabel->visible = !$this->list->items->count;
    }

    public function setContextEditor(AbstractEditor $editor = null)
    {
        $this->contextEditor = $editor;
    }

    /**
     * @return FormEditor
     */
    public function getContextEditor()
    {
        return $this->contextEditor;
    }

    public function setLiveCode($value, $beginLine = 0, $beginPosition = 0)
    {
        $this->liveCodeEditor->setValue($value);

        $this->liveCodeEditor->jumpToLine($beginLine, $beginPosition);
        $this->liveCodeEditor->jumpToLineSpaceOffset($beginLine);
    }

    public function getLiveCode()
    {
        $value = $this->liveCodeEditor->getValue();

        /*if (Str::startsWith($value, "<?\n")) {
            $value = str::sub($value, 3);
        }

        if (Str::startsWith($value, "<?")) {
            $value = str::sub($value, 2);
        }

        $regex = Regex::of("\\/\\/ \\+Actions\\:[ ]+?[0-9]+?[ ]+?//")->with($value);

        if ($regex->matches()) {
            if ($this->list->items->count) {
                $value = $regex->replace("// +Actions: {$this->list->items->count} //");
            } else {
                $value = $regex->replace("");
            }
        } else {
            if ($this->list->items->count) {
                $value = "// +Actions: {$this->list->items->count} //\n" . $value;
            }
        }*/

        return $value;
    }

    public function showAndWait(ActionEditor $editor = null, $class = null, $method = null)
    {
        $this->editor = $editor;

        uiLater(function () {
            $this->constructorSplitPane->dividerPositions = Ide::get()->getUserConfigArrayValue(get_class($this) . ".dividerPositions", $this->constructorSplitPane->dividerPositions);
            $this->useDefaultCheckbox->selected = Ide::get()->getUserConfigValue(CodeEditor::class . '.editorOnDoubleClick') == "constructor";

            /*TimerScript::executeAfter(1000, function () {
                $marker = new ArrowPointMarker(new ActionTypeMarketTarget($this, SleepActionType::class));
                $marker->direction = 'RIGHT';

                $marker->timeout = 10 * 1000;
                $marker->tooltipText = "Добавьте это действие,\n если хотите сделать паузу в коде.";
                $marker->show();

                $marker = new ArrowPointMarker(new CancelButtonMarkerTarget($this));
                $marker->direction = 'AUTO';
                $marker->tooltipText = "Сохраните добавленные действия!";
                $marker->show();
            });*/
        });

        $this->tabs->selectedIndex = Ide::get()->getUserConfigValue(CodeEditor::class . '.tabIndex', 0);

        $this->buildActionTypePane($this->editor);

        $editor->makeSnapshot();

        $this->editor->load();

        $this->class = $class;
        $this->method= $method;

        $actions = $editor->findMethod($class, $method);
        $this->list->items->clear();
        $this->list->items->addAll($actions);

        $this->updateList();
        $this->userData = new \stdClass(); // hack!
        $this->userData->self = $this;

        //$this->tabs->selectedIndex = self::$globalTabSelectedIndex;

        $this->setOnlyIcons(Ide::get()->getUserConfigValue(get_class($this) . ".onlyIcons", $this->isOnlyIcons()));

        parent::showAndWait();
    }

    public function buildActionTypePane(ActionEditor $editor)
    {
        static $buildTabs;
        static $subGroups = [];
        static $managerUpd = 0;

        if ($editor->getManager()->lastUpdated() > $managerUpd) {
            $buildTabs = [];
            $subGroups = [];

            $managerUpd = $editor->getManager()->lastUpdated();

            $actions = $editor->getManager()->getActionTypes();

            $list = [];

            foreach ($actions as $action) {
                if ($action->isDeprecated()) {
                    continue;
                }

                $contexts = $action->forContexts();

                if ($contexts) {
                    $matches = false;

                    foreach ($contexts as $one) {
                        if ($one['class'] && $this->context['class'] == $one['class']) {
                            $matches = true;
                            break;
                        }
                    }

                    if (!$matches) {
                        continue;
                    }
                }

                $group = $action->getGroup();
                $subGroup = $action->getSubGroup();

                $list[$group][$subGroup][] = $action;
            }

            foreach ($list as $group => $elements) {
                foreach ($elements as $subGroup => $actions) {
                    foreach ($actions as $action) {
                        $this->addActionType($action, $buildTabs, $subGroups);
                    }
                }
            }
        }

        $this->actionTypePane->tabs->clear();

        /** @var UXTab $tab */
        $i = 0;
        foreach ($buildTabs as $group => $tab) {
            $t = new UXTab();
            $t->data('group', $group);
            $t->text = $tab->text;
            $t->closable = false;
            $t->content = new UXScrollPane($tab->content);
            $t->content->fitToWidth = true;
            $t->content->data('vbox', $tab->data('vbox'));
            $t->content->data('fbox', $tab->data('fbox'));

            $t->graphic = $tab->graphic;
            $t->style = $tab->style;

            $this->actionTypePane->tabs->add($t);

            if ($i++ == static::$tabSelectedIndex) {
                UXApplication::runLater(function () use ($t) {
                    $this->actionTypePane->selectTab($t);
                });
            }
        }
    }

    public function getAndShowActionType($typeClass)
    {
        /** @var AbstractSimpleActionType $type */
        $type = new $typeClass();

        $group = $type->getGroup();
        foreach ($this->actionTypePane->tabs as $tab) {
            if ($tab->data('group') == $group) {
                $this->actionTypePane->selectTab($tab);

                foreach ($tab->content->content->children as $node) {
                    if ($node->data('type') == $typeClass) {
                        $tab->content->scrollToNode($node);
                        return $node;
                    }
                }

                break;
            }
        }

        return null;
    }

    private function addActionType(AbstractActionType $actionType, &$buildTabs, &$subGroups)
    {
        $tab = $buildTabs[$actionType->getGroup()];
        $subGroup = $actionType->getSubGroup();

        if (!$tab) {
            $tab = new UXTab();
            $tab->style = '-fx-cursor: hand;';
            $tab->closable = false;
            $tab->text = $actionType->getGroup();

            $flowPane = new UXFlowPane();
            $flowPane->hgap = $flowPane->vgap = 6;
            $flowPane->padding = 9;

            $vPane = new UXVBox();
            $vPane->spacing = 2;
            $vPane->padding = 5;

            $flowPane->alignment = $vPane->alignment = 'TOP_LEFT';

            $tab->data('fbox', $flowPane);
            $tab->data('vbox', $vPane);

            $tab->content = $vPane;

            $buildTabs[$actionType->getGroup()] = $tab;
        }

        if (!$subGroups[$actionType->getGroup()][$subGroup]) {
            if ($subGroup) {
                $label = new UXLabel($subGroup);
                $label->font = UXFont::of($label->font, $label->font->size, 'BOLD');

                if ($subGroups[$actionType->getGroup()]) {
                    $label->paddingTop = 5;
                }

                $tab->data('vbox')->add($label);


                $label = new UXLabel($subGroup);
                $label->font = UXFont::of($label->font, $label->font->size, 'BOLD');
                $tab->data('fbox')->observer('width')->addListener(function ($old, $new) use ($label, $tab) {
                    $label->minWidth = $new - $tab->data('fbox')->paddingLeft - $tab->data('fbox')->paddingRight;
                });

                if ($subGroups[$actionType->getGroup()]) {
                    $label->paddingTop = 5;
                }

                $tab->data('fbox')->add($label);
            }

            $subGroups[$actionType->getGroup()][$subGroup] = 1;
        }

        $btn = new UXButton();
        $btn->tooltipText = $actionType->getTitle() . " \n -> " . $actionType->getDescription();
        $btn->graphic = Ide::get()->getImage($actionType->getIcon());
        $btn->height = 29;
        $btn->maxWidth = 99999;
        $btn->alignment = 'CENTER_LEFT';
        $btn->text = $actionType->getTitle();
        $btn->data('type', get_class($actionType));

        $btn->userData = $actionType;
        $btn->classes->add('dn-simple-toggle-button');

        if ($btn->graphic == null) {
            $btn->graphic = ico('blocks16');
        }

        $btn->on('action', [$this, 'actionTypeClick']);

        $dragDetect = function (UXMouseEvent $e) {
            $dragboard = $e->sender->startDrag(['MOVE']);
            $dragboard->dragView = $e->sender->snapshot();

            $dragboard->dragViewOffsetX = $dragboard->dragView->width / 2;
            $dragboard->dragViewOffsetY = $dragboard->dragView->height / 2;

            $dragboard->string = get_class($e->sender->userData);

            $e->consume();
        };

        $btn->on('dragDetect', $dragDetect);

        $tab->data('vbox')->add($btn);

        // ---

        $smallBtn = new UXButton();
        $smallBtn->tooltipText = $btn->tooltipText;
        $smallBtn->graphic = Ide::get()->getImage($actionType->getIcon());
        $smallBtn->size = [34, 34];
        $smallBtn->userData = $actionType;
        $smallBtn->classes->addAll($btn->classes);
        $smallBtn->data('type', get_class($actionType));

        if ($smallBtn->graphic == null) {
            $smallBtn->graphic = ico('blocks16');
        }

        $smallBtn->on('action', [$this, 'actionTypeClick']);
        $smallBtn->on('dragDetect', $dragDetect);

        $tab->data('fbox')->add($smallBtn);
    }


    /**
     * @param UXEvent|null $e
     */
    protected function actionDelete(UXEvent $e = null)
    {
        /** @var ActionConstructorForm $self */
        $self = $e ? $e->sender->scene->window->userData->self : $this;
        $indexes = $self->list->selectedIndexes;

        if ($indexes) {
            $index = -1;

            $self->editor->removeActions($self->class, $self->method, $indexes);
            $actions = [];

            foreach ($indexes as $index) {
                $actions[] = $self->list->items[$index];
            }

            foreach ($actions as $action) {
                $this->list->items->remove($action);
            }

            $this->editor->updateMethod($self->class, $self->method, Items::toArray($this->list->items));

            $this->list->update();

            $this->updateList();

            $this->list->selectedIndex = $index++;

            if ($index >= $this->list->items->count) {
                $this->list->selectedIndex = $this->list->items->count - 1;
            }
        }
    }

    /**
     * @param UXMouseEvent|null $e
     * @event list.click
     */
    public function actionEdit(UXMouseEvent $e = null)
    {
        if (!$e || $e->clickCount >= 2) {
            /** @var ActionConstructorForm $self */
            $self = $e ? $e->sender->scene->window->userData->self : $this;

            $index = $self->list->selectedIndex;

            if ($index > -1) {
                /** @var Action $action */
                $action = $self->list->items[$index];

                /** @var AbstractSimpleActionType $type */
                $type = $action->getType();

                if ($type->showDialog($this, $action, $self->contextEditor)) {
                    $self->list->update();
                    $self->list->selectedIndex = $index;

                    $this->editor->updateMethod($self->class, $self->method, Items::toArray($this->list->items));
                }
            }
        }
    }

    public function addAction(AbstractSimpleActionType $actionType, $index = -1)
    {
        $self = $this;

        $action = new Action($actionType);

        if ($actionType->showDialog($this, $action, $self->contextEditor, true)) {
            $editor = $self->editor;
            $editor->addAction($action, $self->class, $self->method);

            if ($index == -1 || $index > $self->list->items->count) {
                $self->list->items->add($action);
                $index = $self->list->items->count - 1;
            } else {
                $this->list->items->insert($index, $action);
            }

            $editor->calculateLevels(Items::toArray($self->list->items));
            $self->list->update();

            $self->list->selectedIndex = $index;
            $self->list->focusedIndex = $index;

            $self->list->scrollTo($index);

            $self->updateList();
        }
    }

    protected function actionTypeClick(UXEvent $e)
    {
        /** @var $actionType AbstractSimpleActionType */
        $actionType = $e->sender->userData;

        /** @var ActionConstructorForm $self */
        $self = $e->sender->scene->window->userData->self;

        $self->addAction($actionType);
    }

    /**
     * @event hide
     */
    public function hide()
    {
        parent::hide();

        Ide::get()->setUserConfigValue(get_class($this) . ".dividerPositions", $this->constructorSplitPane->dividerPositions);

        if ($this->editor) {
            $this->editor->cacheData = [];
        }
    }

    /**
     * @event clearButton.action
     */
    public function actionClear()
    {
        if (!$this->list->items->count) {
            Dialog::show('Список из действий пуст.');
            return;
        }

        $dlg = new MessageBoxForm('Вы уверены, что хотите удалить все действия?', ['Да, удалить', 'Нет, отмена']);

        if ($dlg->showDialog() && $dlg->getResultIndex() == 0) {
            $this->editor->removeMethod($this->class, $this->method);

            $this->list->items->clear();
            $this->list->update();
            $this->updateList();
        }
    }

    /**
     * @event previewButton.action
     */
    public function actionPreview()
    {
        if (!$this->list->items->count) {
            Dialog::show('Список из действий пуст.');
            return;
        }

        $script = new ActionScript();

        $imports = $script->getImports(Items::toArray($this->list->items));

        $code = $script->compileActions(
            $this->class,
            $this->method,
            Items::toArray($this->list->items),'','',''
        );

        $phpParser = new PhpParser("<?\n\n" . $code);
        $phpParser->addUseImports($imports);

        $dialog = new UXForm();
        $dialog->title = 'Сгенерированный php код';
        $dialog->style = 'UTILITY';
        $dialog->modality = 'APPLICATION_MODAL';
        $dialog->size = [700, 400];

        $area = new UXPhpCodeArea();
        $area->text = $phpParser->getContent();
        UXVBox::setVgrow($area, 'ALWAYS');

        $okButton = new UXButton('Закрыть');
        $okButton->graphic = ico('ok16');
        $okButton->padding = [10, 15];
        $okButton->maxHeight = 9999;
        $okButton->on('action', function () use ($dialog) { $dialog->hide(); });

        $buttons = new UXHBox([$okButton]);
        $buttons->spacing = 10;
        $buttons->height = 40;

        $pane = new UXVBox([$area, $buttons]);
        $pane->spacing = 10;
        $pane->padding = 10;

        UXAnchorPane::setAnchor($pane, 0);

        $dialog->add($pane);
        $dialog->showAndWait();
    }

    /**
     * @event saveButton.action
     */
    public function actionSave()
    {
        $this->editor->clearSnapshots();
        $this->editor->save();
        static::$tabSelectedIndex = $this->actionTypePane->selectedIndex;
        static::$globalTabSelectedIndex = $this->tabs->selectedIndex;

        $this->setResult(true);
        $this->hide();
    }

    /**
     * @event close
     * @event cancelButton.action
     */
    public function actionCancel()
    {
        $this->setResult(false);

        $this->editor->restoreSnapshot();
        $this->editor->clearSnapshots();
        $this->editor->save();
        static::$tabSelectedIndex = $this->actionTypePane->selectedIndex;
        static::$globalTabSelectedIndex = $this->tabs->selectedIndex;

        $this->hide();
    }

    /**
     * @event convertButton.action
     */
    public function actionConvert()
    {
        if ($this->list->items->count == 0) {
            UXDialog::show('Нет действий для конвертирования в php код');
            return;
        }

        $buttons = ['Да, перевести', 'Нет, отмена'];

        $dialog = new MessageBoxForm('Вы уверены, что хотите перевести все действия в php код?', $buttons);

        if ($dialog->showDialog() && $dialog->getResultIndex() == 0) {
            $script = new ActionScript();

            $imports = $script->getImports(Items::toArray($this->list->items));

            $code = $script->compileActions(
                $this->class,
                $this->method,
                Items::toArray($this->list->items),
                'Generated',
                '',
                ''
            );

            $phpParser = new PhpParser($this->getLiveCode());
            $phpParser->addUseImports($imports);

            $code = $phpParser->getCodeOfMethod($this->class, $this->method) . "\n\n" . $code;

            $phpParser->replaceOfMethod($this->class, $this->method, $code);
            $bind = $phpParser->findMethod($this->class, $this->method);

            $this->setLiveCode($phpParser->getContent(), $bind['line'], $bind['pos']);

            $this->editor->removeMethod($this->class, $this->method);
            $this->list->items->clear();

            UXApplication::runLater(function () {
                $this->tabs->selectedIndex = 1;

                UXApplication::runLater(function () {
                    $this->liveCodeEditor->requestFocus();
                });
            });
        }
    }

    public function actionPaste()
    {
        $xmlProcessor = (new XmlProcessor());

        try {
            $xml = $xmlProcessor->parse(UXClipboard::getText());

            $actions = [];
            foreach ($xml->findAll("/actionCopies/*") as $domAction) {
                if ($action = $this->editor->getManager()->buildAction($domAction)) {
                    $actions[] = $action;
                }
            }

            if ($this->list->selectedIndexes) {
                $index = $this->list->selectedIndex;
                $selectedIndexes = Flow::ofRange($index, $index + sizeof($actions))->toArray();

                foreach (Items::reverse($actions) as $action) {
                    $this->list->items->insert($index, $action);
                }
            } else {
                $selectedIndexes = Flow::ofRange($this->list->items->count, $this->list->items->count + sizeof($actions))->toArray();
                $this->list->items->addAll($actions);
            }

            $this->updateList();
            $this->list->update();

            $this->editor->updateMethod($this->class, $this->method, Items::toArray($this->list->items));

            $this->list->selectedIndex = $selectedIndexes;
        } catch (ProcessorException $e) {
            ;
        }
    }

    public function actionCopy()
    {
        $indexes = $this->list->selectedIndexes;

        if ($indexes) {
            $xmlProcessor = (new XmlProcessor());

            $document = $xmlProcessor->createDocument();

            $document->appendChild($root = $document->createElement('actionCopies'));


            foreach ($indexes as $index) {
                /** @var Action $action */
                $action = $this->list->items[$index];

                $element = $document->createElement($action->getType()->getTagName(), [
                    'ideName' => Ide::get()->getName(),
                    'ideVersion' => Ide::get()->getVersion(),
                    'ideNamespace' => Ide::get()->getNamespace(),
                ]);

                $action->getType()->serialize($action, $element, $document);

                $root->appendChild($element);
            }

            UXClipboard::setText($xmlProcessor->format($document));
        }
    }
}