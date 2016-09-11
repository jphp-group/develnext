<?php
namespace ide\forms;

use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ImagePropertyEditor;
use ide\editors\value\TextPropertyEditor;
use ide\forms\mixins\DialogFormMixin;
use php\gui\designer\UXDesignProperties;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXList;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTab;
use php\lib\Items;

/**
 * Class ListPropertyEditorForm
 * @package ide\forms
 *
 * @property UXListView $list
 * @property UXVBox $propPane
 */
class ListPropertyEditorForm extends AbstractIdeForm
{
    use DialogFormMixin;

    /**
     * @var UXDesignProperties
     */
    protected $properties;

    protected function init()
    {
        parent::init();

        $this->list->on('mouseUp', function () {
            if ($this->list->selectedIndex < 0) {
                $this->list->selectedIndex = 0;
            }


            UXApplication::runLater(function () {
                $selected = Items::first($this->list->selectedItems);
                $this->properties->target = $selected ?: new \stdClass();

                $this->propPane->enabled = !!$selected;

                $this->properties->update();
            });
        }, __CLASS__);
    }

    public function setList(UXList $list)
    {
        $this->list->items->clear();
        $this->list->items->addAll($list);
    }

    public function setDefaultProperties()
    {
        $properties = new UXDesignProperties();
        $properties->addGroup('general', 'Свойства элемента');

        $properties->target = new \stdClass();
        $properties->addProperty('general', 'text', 'Текст', new TextPropertyEditor());
        //$properties->addProperty('general', 'graphic', 'Иконка', new ImagePropertyEditor());

        $this->properties = $properties;
    }

    public function setAsTabs()
    {
        $this->setDefaultProperties();
        $this->properties->addProperty('general', 'closable', 'Закрываемый', new BooleanPropertyEditor());

        $this->list->setCellFactory(function (UXListCell $cell, UXTab $tab) {
            $cell->text = $tab->text;
        });
    }

    public function setResult($result)
    {
        if ($result instanceof UXList) {
            $this->setList($result);
        }

        $this->result = $result;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->propPane->children->clear();
        $this->propPane->children->addAll($this->properties->getGroupPanes());

        $this->list->selectedIndex = 0;
        $this->list->trigger('mouseUp');
    }
}