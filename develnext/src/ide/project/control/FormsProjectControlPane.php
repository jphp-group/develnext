<?php
namespace ide\project\control;
use ide\commands\CreateFormProjectCommand;
use ide\editors\AbstractEditor;
use ide\editors\common\FormListEditor;
use ide\editors\FormEditor;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\ProjectFile;
use ide\systems\Cache;
use ide\systems\FileSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use ide\utils\FileUtils;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\lib\fs;

/**
 * @package ide\project\control
 */
class FormsProjectControlPane extends AbstractEditorsProjectControlPane
{
    /**
     * @var FlowListViewDecorator
     */
    protected $list;

    /**
     * @var FormListEditor
     */
    protected $settingsMainFormCombobox;

    public function getName()
    {
        return "Формы";
    }

    public function getDescription()
    {
        return "Формы и окна проекта";
    }

    public function getIcon()
    {
        return 'icons/windows16.png';
    }

    /**
     * @param FormEditor $item
     * @return mixed
     */
    protected function getBigIcon($item)
    {
        return 'icons/window32.png';
    }

    /**
     * @return AbstractEditor[]
     */
    protected function getItems()
    {
        $gui = GuiFrameworkProjectBehaviour::get();
        return $gui ? $gui->getFormEditors() : [];
    }

    /**
     * @return mixed
     */
    protected function doAdd()
    {
        $command = new CreateFormProjectCommand();
        $command->onExecute();
    }

    /**
     * @param FormEditor $item
     * @return UXNode
     */
    protected function makeItemUi($item)
    {
        /** @var ImageBox $box */
        $box = parent::makeItemUi($item);

        $gui = GuiFrameworkProjectBehaviour::get();

        if ($gui && $gui->isMainForm($item)) {
            $box->setTitle($box->getTitle(), '-fx-font-weight: bold;');
        }

        return $box;
    }


    protected function makeAdditionalUi()
    {
        $formListEditor = new FormListEditor();
        $formListEditor->setEmptyItemText('[Нет]');
        $formListEditor->build();

        $formListEditor->onChange(function ($value) {
            $gui = GuiFrameworkProjectBehaviour::get();
            if ($gui) {
                $gui->setMainForm($value);
            }

            $this->refresh(false);
        });

        $formListEditor->getUi()->width = 250;
        $this->settingsMainFormCombobox = $formListEditor;

        $label = new UXLabel('* отображается при старте программы');
        $label->textColor = 'silver';

        $box = new UXHBox([$formListEditor->getUi()], 5);
        $box->alignment = 'CENTER_LEFT';

        $ui = new UXVBox([
            new UXLabel('Главная форма:'),
            $box
        ]);

        $ui->spacing = 3;
        $ui->alignment = 'CENTER_LEFT';

        return [$ui];
    }

    public function refresh($updateUi = true)
    {
        parent::refresh();

        if ($updateUi && $this->settingsMainFormCombobox) {
            $gui = GuiFrameworkProjectBehaviour::get();

            if ($gui) {
                $mainForm = $gui->getMainForm();
            }

            $this->settingsMainFormCombobox->updateUi();

            if ($gui) {
                $gui->setMainForm($mainForm);
                $this->settingsMainFormCombobox->setSelected($mainForm);
            }
        }
    }
}