<?php
namespace ide\editors\common;


use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\ScriptModuleEditor;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\Logger;
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
     * @var string
     */
    protected $targetCode;

    /**
     * @var bool
     */
    protected $enableAllForms;

    protected $cacheItems = null;

    protected $disableForms;

    /**
     * @var string
     */
    protected $emptyItemText = '...';

    /**
     * @var bool
     */
    protected $enableAppModule = true;

    /**
     * @var bool
     */
    protected $disableModules = false;

    /**
     * @var bool
     */
    protected $stringValues = false;

    /**
     * @var string
     */
    protected $formMethod = 'form';

    /**
     * @var string
     */
    protected $moduleMethod = 'module';

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

    /**
     * @return string
     */
    public function getEmptyItemText()
    {
        return $this->emptyItemText;
    }

    /**
     * @param string $emptyItemText
     */
    public function setEmptyItemText($emptyItemText)
    {
        $this->emptyItemText = $emptyItemText;
    }

    public function disableAppModule()
    {
        $this->enableAppModule = false;

        return $this;
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

    public function disableModules()
    {
        $this->disableModules = true;

        return $this;
    }

    public function stringValues()
    {
        $this->stringValues = true;

        return $this;
    }

    /**
     * @param string $formMethod
     * @return $this
     */
    public function formMethod($formMethod)
    {
        $this->formMethod = $formMethod;
        return $this;
    }

    public function enableSender($code = '~sender', $target = '~target')
    {
        $this->senderCode = $code;
        $this->targetCode = $target;

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

        $this->comboBox->onCellRender(new ObjectListEditorCellRender());
        $this->comboBox->onButtonRender(new ObjectListEditorButtonRender());
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

    protected $comboBoxItems = [];

    public function addItem(ObjectListEditorItem $item)
    {
        if ($this->isFiltered($item)) {
            $this->comboBoxItems[] = $item;
        }
    }

    public function updateUi()
    {
        Logger::trace();

        $editor = $this->editor ?: FileSystem::getSelectedEditor();

        $this->comboBox->items->clear();
        $this->comboBoxItems = [];

        $undef = new ObjectListEditorItem();
        $undef->text = $this->emptyItemText;
        $this->comboBox->items->add($undef);

        if ($this->senderCode) {
            $this->addItem(new ObjectListEditorItem('Текущий объект (self)', null, $this->senderCode));
            $this->addItem(new ObjectListEditorItem('Целевой объект (target)', null, $this->targetCode));
            $this->addItem(new ObjectListEditorItem('Созданный объект ($instance)', null, "~instance"));

            if (!$this->disableForms) {
                $this->addItem(new ObjectListEditorItem('Текущая форма', null, $this->senderCode . "Form"));
            }
        }

        $formsAdded = [];

        if ($editor instanceof FormEditor) {
            $isModule = $editor instanceof ScriptModuleEditor;
            $isForm   = !$isModule;

            if (($isModule && !$this->disableModules) || ($isForm && !$this->disableForms)) {
                $this->addItem(new ObjectListEditorItem(
                    $editor->getTitle(),
                    Ide::get()->getImage($editor->getIcon(), [16, 16]),
                    ''
                ));

                $nodes = $editor->getObjectList();

                foreach ($nodes as $item) {
                    $item->level = 1;

                    if ($this->stringValues) {
                        $item->value = "{$editor->getTitle()}.{$item->value}";
                    }

                    $this->addItem($item);
                }
            }

            if (!$this->disableDependencies) {
                $moduleEditors = $editor->getModuleEditors();

                if ($moduleEditors && !$this->disableModules) {
                    $this->addItem(new ObjectListEditorItem('[Модули]', null, ''));

                    foreach ($moduleEditors as $module => $moduleEditor) {
                        $nodes = $moduleEditor->getComponents();

                        if ($nodes) {
                            $this->addItem(new ObjectListEditorItem(
                                "[$module]",
                                Ide::get()->getImage($moduleEditor->getIcon(), [16, 16]),
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
                        $this->addItem(new ObjectListEditorItem('Формы модуля', null, '_context'));

                        foreach ($formEditors as $formEditor) {
                            $formsAdded[$formEditor->getTitle()] = $formEditor;

                            $this->addItem(new ObjectListEditorItem(
                                "{$formEditor->getTitle()}",
                                Ide::get()->getImage($formEditor->getIcon(), [16, 16]),
                                $this->formMethod,
                                1
                            ));

                            $this->appendFormEditor($formEditor, 2);
                        }
                    }
                }
            }
        }

        if ($this->enableAllForms && !$this->disableForms) {
            $project = Ide::get()->getOpenedProject();

            if ($project && $project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
                /** @var GuiFrameworkProjectBehaviour $gui */
                $gui = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                $formEditors = $gui->getFormEditors();

                if (/*!($editor instanceof FormEditor) || sizeof($formEditors) > 1*/true) {
                    $this->addItem(new ObjectListEditorItem('[Другие формы]', null, ''));

                    foreach ($formEditors as $key => $formEditor) {
                        if (FileUtils::hashName($formEditor->getFile()) == FileUtils::hashName($editor->getFile())) {
                            continue;
                        }

                        if (isset($formsAdded[$formEditor->getTitle()])) {
                            continue;
                        }

                        if ($this->stringValues) {
                            $prefix = "{$formEditor->getTitle()}.";
                        } else {
                            $prefix = "{$this->formMethod}('{$formEditor->getTitle()}')";
                        }

                        $this->addItem(new ObjectListEditorItem(
                            $formEditor->getTitle(),
                            Ide::get()->getImage($formEditor->getIcon(), [16, 16]),
                            $prefix,
                            1
                        ));

                        $this->appendFormEditor($formEditor, 2, $prefix);
                    }
                }
            }
        }

        if ($this->enableAppModule && !$this->disableModules) {
            $gui = GuiFrameworkProjectBehaviour::get();

            if ($gui) {
                $appModule = $gui->getModuleEditor('AppModule');

                if ($editor && ($appModule && $editor && FileUtils::equalNames($appModule->getFile(), $editor->getFile()))) {
                    // ...
                } else {
                    if ($appModule) {
                        $this->addItem(new ObjectListEditorItem(
                            $appModule->getTitle(), Ide::get()->getImage($appModule->getIcon(), [16, 16]), 'appModule()'
                        ));

                        $this->appendFormEditor($appModule, 1, 'appModule()');
                    }
                }
            }
        }

        $this->comboBox->items->addAll($this->comboBoxItems);
        Logger::trace("update done.");
    }

    protected function appendFormEditor(FormEditor $formEditor, $level = 0, $prefix = '')
    {
        $list = $formEditor->getObjectList();

        if ($list) {
            foreach ($list as $item) {
                $new = $item; //->duplicate();
                $new->value = $prefix ? ($this->stringValues ? "{$prefix}{$item->text}" : "{$prefix}->{$item->text}") : $item->text;
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