<?php
namespace ide\forms;

use ide\action\AbstractActionType;
use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\action\ActionEditor;
use ide\editors\FormEditor;
use ide\editors\menu\ContextMenu;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXContextMenu;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\lib\Items;

/**
 * Class ActionConstructorForm
 * @package ide\forms
 *
 * @property UXListView $list
 * @property UXTabPane $actionTypePane;
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

        $this->list->setDraggableCellFactory([$this, 'listCellFactory'], [$this, 'listDragDone']);
        $this->hintLabel->mouseTransparent = true;

        $contextMenu = new ContextMenu();

        $contextMenu->addCommand(AbstractCommand::make('Редактировать', 'icons/edit16.png', function () {
            $this->actionEdit();
        }));

        $contextMenu->addSeparator();

        $contextMenu->addCommand(AbstractCommand::make('Удалить', 'icons/delete16.png', function () {
            $this->actionDelete();
        }, 'Delete'));

        $this->list->contextMenu = $contextMenu->getRoot();
    }

    protected function listDragDone($dragIndex, $replaceIndex)
    {
        $this->editor->updateMethod($this->class, $this->method, Items::toArray($this->list->items));

        $this->updateList();

        $this->editor->calculateLevels(Items::toArray($this->list->items));
        $this->list->update();

        if ($replaceIndex < $this->list->items->count) {
            $this->list->selectedIndex = $replaceIndex;
        }
    }

    protected function listCellFactory(UXListCell $cell, Action $action = null, $empty)
    {
        if ($action) {
            $titleName = new UXLabel($action->getTitle());
            $titleName->style = '-fx-font-weight: bold;';

            if ($action->getDescription()) {
                $titleDescription = new UXLabel($action->getDescription());
                $titleDescription->style = '-fx-text-fill: gray;';
            } else {
                $titleDescription = null;
            }

            $title = new UXVBox($titleDescription ? [$titleName, $titleDescription] : [$titleName]);
            $title->spacing = 0;

            $image = Ide::get()->getImage($action->getIcon());

            if (!$image) {
                $image = Ide::get()->getImage('icons/blocks16.png');
            }

            $line = new UXHBox([$image, $title]);
            $line->spacing = 10;
            $line->padding = 5;

            $line->alignment = 'CENTER_LEFT';

            $line->paddingLeft = 20 * $action->getLevel() + 5;

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
                $label->minWidth = 34 * 2;

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

        $tab->content->add($btn);
    }


    /**
     * @param UXEvent|null $e
     */
    protected function actionDelete(UXEvent $e = null)
    {
        /** @var ActionConstructorForm $self */
        $self = $e ? $e->sender->scene->window->userData->self : $this;
        $index = $self->list->selectedIndex;

        if ($index > -1) {
            $action = $self->list->items[$index];

            $self->editor->removeAction($self->class, $self->method, $index);
            $this->list->items->remove($action);

            $this->list->selectedIndex = $index++;

            if ($index >= $this->list->items->count) {
                $this->list->selectedIndex = $this->list->items->count - 1;
            }

            $this->editor->updateMethod($self->class, $self->method, Items::toArray($this->list->items));
            $this->list->update();

            $this->updateList();
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

    protected function actionTypeClick(UXEvent $e)
    {
        /** @var $actionType AbstractSimpleActionType */
        $actionType = $e->sender->userData;

        /** @var ActionConstructorForm $self */
        $self = $e->sender->scene->window->userData->self;
        $self->updateList();

        $action = new Action($actionType);

        if ($actionType->showDialog($action, $self->contextEditor)) {
            $editor = $self->editor;
            $editor->addAction($action, $self->class, $self->method);

            $self->list->items->add($action);

            $editor->calculateLevels(Items::toArray($self->list->items));
            $self->list->update();

            $index = $self->list->items->count - 1;

            $self->list->selectedIndex = $index;
            $self->list->focusedIndex = $index;

            $self->list->scrollTo($index);

            $self->updateList();
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
}