<?php
namespace ide\forms;

use ide\action\AbstractActionType;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionEditor;
use ide\action\ActionScript;
use ide\editors\CodeEditor;
use ide\editors\common\CodeTextArea;
use ide\editors\FormEditor;
use ide\editors\menu\ContextMenu;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\utils\PhpParser;
use php\format\ProcessorException;
use php\gui\event\UXDragEvent;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXClipboard;
use php\gui\UXContextMenu;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\lang\IllegalStateException;
use php\lib\Items;
use php\lib\Str;
use php\util\Flow;
use php\xml\XmlProcessor;

/**
 * Class ActionConstructorForm
 * @package ide\forms
 *
 * @property UXListView $list
 * @property UXTabPane $actionTypePane
 * @property UXAnchorPane $generatedCodeContent
 * @property UXTabPane $tabs
 */
class ActionConstructorForm extends AbstractForm
{
    /**
     * @var ActionEditor
     */
    protected $editor;

    /**
     * @var FormEditor
     */
    protected $contextEditor;

    /** @var string */
    protected $class;

    /** @var string */
    protected $method;

    protected static $tabSelectedIndex = -1;

    protected function init()
    {
        parent::init();

        $codeView = new CodeTextArea('php', [
            'fontSize' => 13,
            'theme' => 'ambiance'
        ]);

        UXAnchorPane::setAnchor($codeView, 0);
        $this->generatedCodeContent->add($codeView);

        $this->tabs->tabs[1]->on('change', function () use ($codeView) {
            UXApplication::runLater(function () use ($codeView) {

                $script = new ActionScript();

                $imports = $script->getImports(Items::toArray($this->list->items));

                $code = "<?php \n";

                if ($imports) {
                    $code .= "// Импортируем имена классов ...\n";
                }

                foreach ($imports as $import) {
                    $code .= "use $import[0];\n";
                }

                if ($imports) {
                    $code .= "\n// Исходный код события \n";
                }

                $code .= $script->compileActions(
                    $this->class,
                    $this->method,
                    Items::toArray($this->list->items),
                    "",
                    '',
                    ''
                );

                $codeView->setValue($code);
            });
        });


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

    public function setContextEditor(FormEditor $editor = null)
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

    public function showAndWait(ActionEditor $editor = null, $class = null, $method = null)
    {
        $this->buildActionTypePane($editor);

        $editor->makeSnapshot();

        $this->editor = $editor;
        $this->editor->load();

        $this->class = $class;
        $this->method= $method;

        $actions = $editor->findMethod($class, $method);

        $this->list->items->clear();
        $this->list->items->addAll($actions);

        $this->updateList();
        $this->userData = new \stdClass(); // hack!
        $this->userData->self = $this;

        parent::showAndWait();
    }

    public function buildActionTypePane(ActionEditor $editor)
    {
        static $buildTabs;
        static $subGroups = [];

        if (!$buildTabs) {
            $buildTabs = [];

            $actions = $editor->getManager()->getActionTypes();

            $list = [];

            foreach ($actions as $action) {
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
        foreach ($buildTabs as $tab) {
            $t = new UXTab();
            $t->text = $tab->text;
            $t->closable = false;
            $t->content = $tab->content;
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

    private function addActionType(AbstractActionType $actionType, &$buildTabs, &$subGroups)
    {
        $tab = $buildTabs[$actionType->getGroup()];
        $subGroup = $actionType->getSubGroup();

        if (!$tab) {
            $tab = new UXTab();
            $tab->closable = false;
            $tab->text = $actionType->getGroup();

            $tab->content = new UXFlowPane();
            $tab->content->hgap = 6;
            $tab->content->vgap = 6;

            $tab->content->padding = 11;
            $tab->style = '-fx-cursor: hand;';

            $tab->content->alignment = 'TOP_LEFT';

            $buildTabs[$actionType->getGroup()] = $tab;
        }

        if (!$subGroups[$actionType->getGroup()][$subGroup]) {
            if ($subGroup) {
                $label = new UXLabel($subGroup);
                $label->minWidth = 34 * 3;

                if ($subGroups[$actionType->getGroup()]) {
                    $label->paddingTop = 5;
                }

                $tab->content->add($label);
            }

            $subGroups[$actionType->getGroup()][$subGroup] = 1;
        }

        $btn = new UXButton();
        $btn->tooltipText = $actionType->getTitle() . " \n -> " . $actionType->getDescription();
        $btn->graphic = Ide::get()->getImage($actionType->getIcon());
        $btn->size = [34, 34];
        $btn->userData = $actionType;
        $btn->style = '-fx-background-color: white; -fx-border-color: silver; -fx-border-width: 1px; -fx-border-radius: 3px;';

        if ($btn->graphic == null) {
            $btn->graphic = ico('blocks16');
        }

        $btn->on('action', [$this, 'actionTypeClick']);

        $btn->on('dragDetect', function (UXMouseEvent $e) {
            $dragboard = $e->sender->startDrag(['MOVE']);
            $dragboard->dragView = $e->sender->snapshot();

            $dragboard->dragViewOffsetX = $dragboard->dragView->width / 2;
            $dragboard->dragViewOffsetY = $dragboard->dragView->height / 2;

            $dragboard->string = get_class($e->sender->userData);

            $e->consume();
        });

        $tab->content->add($btn);
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

                if ($type->showDialog($action, $self->contextEditor)) {
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

        if ($actionType->showDialog($action, $self->contextEditor, true)) {
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

    public function hide()
    {
        parent::hide();

        if ($this->editor) {
            $this->editor->cacheData = [];
        }
    }


    /**
     * @event saveButton.action
     */
    public function actionSave()
    {
        $this->editor->clearSnapshots();
        $this->editor->save();
        static::$tabSelectedIndex = $this->actionTypePane->selectedIndex;

        $this->hide();
    }

    /**
     * @event cancelButton.action
     */
    public function actionCancel()
    {
        $this->editor->restoreSnapshot();
        $this->editor->clearSnapshots();
        $this->editor->save();
        static::$tabSelectedIndex = $this->actionTypePane->selectedIndex;

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

        $buttons = ['Конвертировать, удалив действия', 'Конвертировать, сохранив действия', 'Отмена'];

        $dialog = new MessageBoxForm('Каким образом сконвертировать действия в php код?', $buttons);

        if ($dialog->showDialog()) {
            switch ($dialog->getResultIndex()) {
                case 0:
                    $this->editor->removeMethod($this->class, $this->method);
                    // ! do not break !

                case 1:
                    $script = new ActionScript();

                    $imports = $script->getImports(Items::toArray($this->list->items));

                    $code = $script->compileActions(
                        $this->class,
                        $this->method,
                        Items::toArray($this->list->items),
                        'Сгенерированный код',
                        '------------------'
                    );

                    $formEditor = $this->editor->getFormEditor();

                    if (!$formEditor) {
                        throw new IllegalStateException();
                    }

                    // TODO FIX!
                    if ($formEditor->getDefaultEventEditor(false) == 'php') {
                        $formEditor->switchToSmallSource();
                    }

                    $formEditor->addUseImports($imports);
                    $formEditor->insertCodeToMethod($this->class, $this->method, $code);

                    $this->actionSave();
                    $this->hide();

                    Timer::run(500, function () use ($formEditor) {
                        $formEditor->jumpToClassMethod($this->class, $this->method);
                    });

                    break;
            }
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