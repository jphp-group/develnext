<?php
namespace ide\forms;

use ide\build\AbstractBuildType;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\Logger;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\lib\Items;

/**
 * Class BuildProjectForm
 * @package ide\forms
 *
 * @property UXListView $list
 * @property UXButton
 */
class BuildProjectForm extends AbstractIdeForm
{
    use SavableFormMixin;

    /**
     * @var AbstractBuildType[]
     */
    protected $buildTypes = [];

    public function setBuildTypes($buildTypes)
    {
        $this->buildTypes = $buildTypes;
    }

    protected function init()
    {
        $this->icon->image = Ide::get()->getImage('icons/box32.png')->image;

        $this->list->setCellFactory(function (UXListCell $cell, AbstractBuildType $item = null, $empty) {
            if ($item) {
                $titleName = new UXLabel($item->getName());
                $titleName->style = '-fx-font-weight: bold;';
                $titleName->padding = 0;

                $titleDescription = new UXLabel($item->getDescription());
                $titleDescription->style = '-fx-text-fill: gray;';

                $box = new UXHBox([$titleName]);
                $box->spacing = 0;

                if ($item->getConfigForm()) {
                    $settingsLink = new UXHyperlink('(настройки)');
                    $settingsLink->padding = [0, 5];
                    $settingsLink->on('action', function () use ($item) {
                        $item->showConfigDialog();
                    });

                    $box->add($settingsLink);
                }

                $title = new UXVBox([$box, $titleDescription]);
                $title->spacing = 0;

                $list = [];

                if ($item->getIcon()) {
                    $list[] = Ide::get()->getImage($item->getIcon());
                }

                $list[] = $title;

                $line = new UXHBox($list);

                $line->spacing = 7;
                $line->padding = 5;

                $cell->text = null;
                $cell->graphic = $line;
            }
        });
    }

    /**
     * @event show
     */
    public function doShow()
    {
        Logger::info("Show build project dialog");

        $this->list->items->addAll($this->buildTypes);
        $this->list->selectedIndexes = [0];
    }

    /**
     * @event cancelButton.action
     */
    public function doCancelButtonClick()
    {
        $this->hide();
    }

    /**
     * @param UXMouseEvent $e
     * @event list.click
     */
    public function doListClick(UXMouseEvent $e)
    {
        if ($e->clickCount > 1) {
            $this->doBuildButtonClick();
        }
    }

    /**
     * @event buildButton.action
     */
    public function doBuildButtonClick()
    {
        /** @var AbstractBuildType $buildType */
        $buildType = Items::first($this->list->selectedItems);

        if ($buildType) {
            if ($buildType->fetchConfig()) {
                $this->hide();
                $buildType->onExecute(Ide::get()->getOpenedProject());
            }
        } else {
            UXDialog::show('Данная функция находится в разработке.', 'INFORMATION');
        }
    }
}