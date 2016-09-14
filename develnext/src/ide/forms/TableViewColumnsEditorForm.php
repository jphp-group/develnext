<?php
namespace ide\forms;

use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\editors\value\PositionPropertyEditor;
use ide\editors\value\SimpleTextPropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXDragEvent;
use php\gui\event\UXEvent;
use php\gui\layout\UXVBox;
use php\gui\UXDialog;
use php\gui\UXImageView;
use php\gui\UXList;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTableColumn;
use php\lib\arr;
use php\lib\str;
use php\util\Regex;

/**
 * Class TableViewColumnsEditorForm
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXListView $list
 * @property UXVBox $content
 */
class TableViewColumnsEditorForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var UXDesignProperties
     */
    protected $properties;

    /**
     * @var bool
     */
    protected $constrainedResizePolicy = false;

    /**
     * TableViewColumnsEditorForm constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return boolean
     */
    public function isConstrainedResizePolicy()
    {
        return $this->constrainedResizePolicy;
    }

    /**
     * @param boolean $constrainedResizePolicy
     */
    public function setConstrainedResizePolicy($constrainedResizePolicy)
    {
        $this->constrainedResizePolicy = $constrainedResizePolicy;
    }

    protected function init()
    {
        parent::init();

        $this->style = 'UTILITY';
        $this->title = 'Редактор колонок';
        $this->resizable = false;
        $this->modality = 'APPLICATION_MODAL';

        $cellFactory = function (UXListCell $cell, UXTableColumn $column) {
            $cell->text = $column->id;
            $cell->textColor = $column->visible ? 'black' : 'gray';
        };
        $this->list->setCellFactory($cellFactory);
        $this->list->setDraggableCellFactory($cellFactory, function (UXDragEvent $e, UXListView $view, $index) {
            $sIndex = $view->selectedIndex;

            if ($sIndex < $index) {
                $view->items->insert($index + 1, $item = $view->selectedItem);
                $view->selectedIndex = $index + 1;
                $view->items->removeByIndex($sIndex);
            } else {
                $view->items->insert($index, $item = $view->selectedItem);
                $view->items->removeByIndex($sIndex + 1);
                $view->selectedIndex = $view->items->indexOf($item);
            }

            $this->getResult()->clear();
            $this->getResult()->addAll($view->items);

            $e->consume();
        });

        $this->list->on('mouseDown', function () {
            $this->properties->target = $this->list->selectedItem;
            $this->properties->update();

            if (!$this->properties->target) {
                $this->properties->target = new \stdClass();

                foreach ($this->properties->getGroupPanes() as $pane) {
                    $pane->visible = false;
                }
                $this->removeButton->enabled = false;
                $this->clearButton->enabled = false;
            } else {
                foreach ($this->properties->getGroupPanes() as $pane) {
                    $pane->visible = true;
                }
                $this->removeButton->enabled = true;
                $this->clearButton->enabled = true;
            }
        });

        $this->properties = new UXDesignProperties();
        $this->properties->target = new \stdClass();
        $this->properties->addGroup('general', 'Свойства колонки');

        $change = function () {
            $index = $this->list->selectedIndex;
            $this->list->update();
            $this->list->selectedIndex = $index;
        };

        $editor = new SimpleTextPropertyEditor();
        $editor->on('change', $change);
        $this->properties->addProperty('general', 'id', 'ID', $editor);

        $this->properties->addProperty('general', 'text', 'Текст', new TextPropertyEditor());
        $this->properties->addProperty('general', 'width', 'Ширина', (new IntegerPropertyEditor())->setSetter(function (ElementPropertyEditor $editor, $value) {
            $target = $editor->designProperties->target;

            if ($this->constrainedResizePolicy) {
                $target->minWidth = $value;
            }

            $target->{$editor->code} = $value;
        })->setGetter(function (ElementPropertyEditor $editor) {
            $target = $editor->designProperties->target;

            if ($this->constrainedResizePolicy) {
                return $target->minWidth;
            }

            return $target->{$editor->code};
        }));

        $editor = new IntegerPropertyEditor(null,  function (ElementPropertyEditor $editor, $value) {
            $target = $editor->designProperties->target;

            if (!$this->constrainedResizePolicy) {
                $value = max($value, $target->width);
            }

            $target->{$editor->code} = $value;
        });

        $this->properties->addProperty('general', 'maxWidth', 'Макс. ширина', $editor);
        $this->properties->addProperty('general', 'resizable', 'Изменяемые размеры', new BooleanPropertyEditor());
        $this->properties->addProperty('general', 'alignment', 'Выравнивание', (new PositionPropertyEditor())->setSetter(function (ElementPropertyEditor $editor, $value) {
            if (!($this->properties->target instanceof \stdClass)) {
                $value = str::replace(str::lower($value), '_', '-');

                $regex = Regex::of('-fx-alignment\\:[- a-zA-Z]{1,}(;)?')->with($this->properties->target->style);

                if ($regex->find()) {
                    $this->properties->target->style = $regex->replace("-fx-alignment: $value;");
                } else {
                    $this->properties->target->style = "-fx-alignment: $value; {$this->properties->target->style}";
                }

                $this->properties->update();
            }
        })->setGetter(function () {
            $target = $this->properties->target;

            $regex = Regex::of('-fx-alignment\\:[ ]+?([-a-zA-Z]{1,})')->with($target->style);

            $value = '';

            if ($regex->find()) {
                $value = str::replace(str::upper(str::trim($regex->group(1))), "-", "_");
            }

            if (!$value) {
                $value = 'CENTER_LEFT';
            }

            return $value;
        }));

        $this->properties->addProperty('general', 'style', 'CSS стиль', new TextPropertyEditor());

        $editor = new BooleanPropertyEditor();
        $editor->on('change', $change);

        $this->properties->addProperty('general', 'visible', 'Видимая', $editor);

        $this->content->children->addAll($this->properties->getGroupPanes());
    }

    /**
     * @event showing
     */
    public function doShowing()
    {
        $this->list->items->clear();
        $this->list->items->addAll(arr::toArray($this->getResult()));
        $this->list->selectedIndex = 0;
        $this->list->trigger('mouseDown', UXEvent::makeMock($this->list));
    }

    /**
     * @event removeButton.action
     */
    public function doDelete()
    {
        /** @var UXTableColumn $item */
        $item = $this->list->selectedItem;

        if (MessageBoxForm::confirmDelete("Колонку {$item->id}", $this)) {
            $this->getResult()->remove($item);
            $this->doShowing();
        }
    }

    /**
     * @event clearButton.action
     */
    public function doClear()
    {
        if (MessageBoxForm::confirm('Вы уверены, что хотите удалить все колонки?', $this)) {
            $this->getResult()->clear();
            $this->doShowing();
        }
    }

    /**
     * @event close
     * @event closeButton.action
     */
    public function doClose()
    {
        $this->setResult(true);
        $this->hide();
    }

    /**
     * @event addButton.action
     */
    public function doAdd()
    {
        if ($id = UXDialog::input('Введите ID для новой колонки:')) {
            $column = new UXTableColumn();
            $column->id = $id;
            $column->text = str::upperFirst($id);
            $column->sortable = false;

            $this->getResult()->add($column);
            $this->doShowing();

            $this->list->selectedIndex = sizeof($this->getResult()) - 1;
            $this->list->trigger('click', UXEvent::makeMock($this->list));
        }
    }
}