<?php
namespace ide\editors\value;

use ide\editors\FormEditor;
use ide\forms\FontPropertyEditorForm;
use ide\forms\ListPropertyEditorForm;
use ide\forms\MessageBoxForm;
use ide\forms\ModuleListEditorForm;
use ide\systems\FileSystem;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\text\UXFont;
use php\gui\UXList;
use php\gui\UXTab;
use php\lib\Items;
use php\lib\Str;
use php\lib\String;
use php\util\Flow;
use php\util\Scanner;

class TabsPropertyEditor extends TextPropertyEditor
{
    public function __construct(callable $getter = null, callable $setter = null)
    {
        parent::__construct($getter, function ($editor, $value) {
            $this->designProperties->target->{$this->code}->clear();
            $this->designProperties->target->{$this->code}->addAll($value);
        });
    }


    public function makeUi()
    {
        $result = parent::makeUi();

        /*$this->editorForm = new ListPropertyEditorForm();
        $this->editorForm->setAsTabs();   */

        $this->textField->promptText = 'нажмите для редактирования';
        $this->textField->editable = false;
        $this->textField->on('click', function (UXMouseEvent $e) {
            $this->showDialog($e->screenX, $e->screenY);
        });

        $this->dialogButton->on('click', function (UXMouseEvent $e) {
            $this->showDialog($e->screenX, $e->screenY);
        });

        return $result;
    }

    public function getNormalizedValue($value)
    {
        return $value;
    }


    public function getCode()
    {
        return 'tabs';
    }

    public function updateUi($value, $noRefreshDesign = false)
    {
        parent::updateUi(Str::join(Flow::of($value)->map(function (UXTab $it) { return $it->text; }), ', '), $noRefreshDesign);
    }

    public function applyValue($value, $updateUi = true)
    {
        $scanner = new Scanner($value, 'UTF-8');

        $items = [];

        while ($scanner->hasNextLine()) {
            $line = $scanner->nextLine();

            $items[] = $line;
        }

        $list = $this->designProperties->target->{$this->code};

        if ($list instanceof UXList) {
            foreach ($list as $i => $tab) {
                if ($items[$i]) {
                    $tab->text = $items[$i];
                } else {
                    break;
                }
            }

            $diff = sizeof($items) - $list->count;

            if ($diff > 0) {
                $count = $list->count + $diff;
                for ($i = $list->count; $i < $count; $i++) {
                    $object = new UXTab();
                    $object->text = $items[$i];
                    $object->content = new UXAnchorPane();

                    $list->add($object);
                }
            } elseif ($diff < 0) {
                $titles = [];
                for ($i = 0; $i < abs($diff); $i++) {
                    $titles[] = $list[sizeof($items) + $i]->text;
                }

                $titles = '"' . Str::join($titles, '", "') . '"';

                $dialog = new MessageBoxForm("Вы уверены, что хотите удалить табы со всем содержимым - $titles?", ['Да, удалить', 'Нет']);

                if ($dialog->showDialog()) {
                    switch ($dialog->getResultIndex()) {
                        case 0:
                            $editor = FileSystem::getSelectedEditor();

                            for ($i = 0; $i < abs($diff); $i++) {
                                $tab = $list[sizeof($items)];

                                $list->removeByIndex(sizeof($items));

                                if ($editor instanceof FormEditor) {
                                    $editor->deleteNode($tab->content);
                                }
                            }

                            break;
                    }
                }
            }

            $this->updateUi($list);
        }
    }


    public function showDialog($x = null, $y = null)
    {
        $dialog = $this->getEditorForm();

        if ($dialog->visible) {
            $dialog->hide();
        }

        $dialog->title = $this->name;
        $dialog->setResult(Flow::of($this->getValue())->map(function ($el) { return $el->text; })->toString("\n"));

        if ($dialog->showDialog($x, $y)) {
            $this->applyValue($dialog->getResult());
        }
    }
}