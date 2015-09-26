<?php
namespace ide\editors\common;


use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\ScriptModuleEditor;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\scripts\AbstractScriptComponent;
use ide\scripts\elements\MacroScriptComponent;
use ide\scripts\ScriptComponentContainer;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use php\gui\layout\UXHBox;
use php\gui\paint\UXColor;
use php\gui\UXApplication;
use php\gui\UXComboBox;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXNode;
use php\lib\Items;

class ObjectListEditor
{
    /** @var UXComboBox */
    protected $comboBox;

    /** @var AbstractEditor|null */
    protected $editor;

    /** @var bool */
    protected $disableDependencies = false;

    /**
     * @var callable[]
     */
    protected $filters = [];

    /**
     * @var callable
     */
    protected $onChange = null;

    /**
     * @var string
     */
    protected $senderCode;

    /**
     * @var bool
     */
    protected $enableAllForms;

    protected $cacheItems = null;

    protected $disableForms;

    /**
     * ObjectListEditor constructor.
     * @param AbstractEditor $editor
     * @param array $filters
     */
    public function __construct(AbstractEditor $editor = null, array $filters = [])
    {
        $this->editor = $editor;
        $this->filters = $filters;
    }

    public function disableDependencies()
    {
        $this->disableDependencies = true;

        return $this;
    }

    public function disableForms()
    {
        $this->disableForms = true;

        return $this;
    }

    public function enableSender($code = '~sender')
    {
        $this->senderCode = $code;

        return $this;
    }

    public function enableAllForms()
    {
        $this->enableAllForms = true;

        return $this;
    }

    public function build()
    {
        $this->makeUi();
        $this->updateUi();
    }

    /**
     * @param $filter (AbstractFormElement $element, UXNode $node)
     * @return $this
     */
    public function addFilter($filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    protected function makeUi()
    {
        $this->comboBox = new UXComboBox();
        $this->comboBox->visibleRowCount = 30;

        $this->comboBox->on('action', function () {
            if ($this->onChange) {
                $handle = $this->onChange;
                $handle($this->getSelected());
            }
        }, 'ext');

        $render = function (UXListCell $cell, ObjectListEditorItem $item) {
            $cell->graphic = null;
            $cell->text = null;

            $label = new UXLabel($item->text);
            $label->graphic = $item->graphic;

            $label->paddingLeft = $item->level * 10;

            $hintLabel = new UXLabel($item->hint ? ": $item->hint" : "");
            $hintLabel->textColor = UXColor::of('gray');

            $cell->graphic = new UXHBox([$label, $hintLabel]);
        };

        $this->comboBox->onCellRender($render);
        $this->comboBox->onButtonRender(function (UXListCell $cell, ObjectListEditorItem $item) {
            $cell->graphic = null;
            $cell->text = null;

            $label = new UXLabel($item->prefix ? $item->prefix . '.' . $item->text : $item->text);
            $label->graphic = $item->graphic ? new UXImageView($item->graphic->image) : null;
            $label->textColor = UXColor::of('black');

            $hintLabel = new UXLabel($item->hint ? ": $item->hint" : "");
            $hintLabel->textColor = UXColor::of('gray');

            $cell->graphic = new UXHBox([$label, $hintLabel]);
        });
    }

    public function setSelected($value)
    {
        $this->comboBox->selectedIndex = 0;

        /** @var ObjectListEditorItem $item */
        foreach ($this->comboBox->items as $i => $item) {
            if ($i > 0 && $item->value === $value) {
                $this->comboBox->selectedIndex = $i;
                break;
            }
        }
    }

    public function getSelected()
    {
        if ($this->comboBox->selectedIndex == 0) {
            return null;
        }

        $item = $this->comboBox->selected;

        if (!$item) {
            return null;
        }

        return $item->value !== null ? $item->value : $item->text;
    }

    public function onChange(callable $handle)
    {
        $this->onChange = $handle;

        return $this;
    }

    /**
     * @return UXNode
     */
    public function getUi()
    {
        return $this->comboBox;
    }

    public function isFiltered(ObjectListEditorItem $item)
    {
        if ($this->filters) {
            foreach ($this->filters as $filter) {
                if ($filter($item)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    public function addItem(ObjectListEditorItem $item)
    {
        if ($this->isFiltered($item)) {
            $this->comboBox->items->add($item);
        }
    }

    public function updateUi()
    {
        $editor = $this->editor ?: FileSystem::getSelectedEditor();

        $this->comboBox->items->clear();

        $undef = new ObjectListEditorItem();
        $undef->text = '...';
        $this->comboBox->items->add($undef);

        if ($this->senderCode) {
            $this->addItem(new ObjectListEditorItem('Текущий объект', null, $this->senderCode));

            if (!$this->disableForms) {
                $this->addItem(new ObjectListEditorItem('Текущая форма', null, $this->senderCode . "Form"));
            }
        }

        if ($editor instanceof FormEditor) {
            if (!$this->disableForms) {
                $this->addItem(new ObjectListEditorItem(
                    $editor->getTitle(),
                    Ide::get()->getImage($editor->getIcon()),
                    ''
                ));

                $nodes = $editor->getDesigner()->getNodes();

                foreach ($nodes as $node) {
                    /** @var AbstractFormElement $element */
                    $element = $editor->getFormat()->getFormElement($node);

                    $item = new ObjectListEditorItem(
                        $editor->getNodeId($node), Ide::get()->getImage($element->getIcon()), null, 1
                    );

                    $item->hint = $element->getName();
                    $item->element = $element;

                    $this->addItem($item);
                }
            }

            if (!$this->disableDependencies) {
                $moduleEditors = $editor->getModuleEditors();

                if ($moduleEditors) {
                    $this->comboBox->items->add(new ObjectListEditorItem('[Модули]', null, ''));

                    foreach ($moduleEditors as $module => $moduleEditor) {
                        $nodes = $moduleEditor->getManager()->getComponents();

                        if ($nodes) {
                            $this->comboBox->items->add(new ObjectListEditorItem(
                                "[$module]",
                                Ide::get()->getImage($moduleEditor->getIcon()),
                                '',
                                1
                            ));

                            $this->appendFormEditor($moduleEditor, 2);
                        }
                    }
                }

                if ($editor instanceof ScriptModuleEditor && !$this->disableForms) {
                    $formEditors = $editor->getFormEditors();

                    if ($formEditors) {
                        $this->comboBox->items->add(new ObjectListEditorItem('Формы модуля', null, '_context'));

                        foreach ($formEditors as $formEditor) {
                            $this->comboBox->items->add(new ObjectListEditorItem(
                                "{$formEditor->getTitle()}",
                                Ide::get()->getImage($formEditor->getIcon()),
                                'form',
                                1
                            ));

                            $this->appendFormEditor($formEditor, 2);
                        }
                    }
                }
            }

            if ($this->enableAllForms) {
                $project = Ide::get()->getOpenedProject();

                if ($project && $project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
                    /** @var GuiFrameworkProjectBehaviour $gui */
                    $gui = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                    $formEditors = $gui->getFormEditors();

                    if (sizeof($formEditors) > 1) {
                        $this->comboBox->items->add(new ObjectListEditorItem('[Другие формы]', null, ''));

                        foreach ($formEditors as $key => $formEditor) {
                            if (FileUtils::hashName($formEditor->getFile()) == FileUtils::hashName($editor->getFile())) {
                                continue;
                            }

                            $prefix = "form('{$formEditor->getTitle()}')";

                            $this->comboBox->items->add(new ObjectListEditorItem(
                                $formEditor->getTitle(),
                                Ide::get()->getImage($formEditor->getIcon()),
                                $prefix,
                                1
                            ));

                            $this->appendFormEditor($formEditor, 2, $prefix);
                        }
                    }
                }
            }
        }
    }

    protected function appendFormEditor(FormEditor $formEditor, $level = 0, $prefix = '')
    {
        $list = $formEditor->getObjectList();

        if ($list) {
            foreach ($list as $item) {
                $new = $item->duplicate();
                $new->value = $prefix ? "{$prefix}->$item->text" : $item->text;
                $new->level = $level;
                $new->prefix = $formEditor->getTitle();

                $this->addItem($new);
            }
        }
    }

    public function clearCache()
    {
        $this->cacheItems = null;

        if ($this->editor) {
            $this->editor->cacheData[__CLASS__ . "_cache"] = null;
        }
    }
}