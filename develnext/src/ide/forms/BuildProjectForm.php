<?php
namespace ide\forms;

use ide\build\AbstractBuildType;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXDialog;
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
class BuildProjectForm extends AbstractForm
{
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

                $titleDescription = new UXLabel($item->getDescription());
                $titleDescription->style = '-fx-text-fill: gray;';

                $title = new UXVBox([$titleName, $titleDescription]);
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
        $this->list->items->addAll($this->buildTypes);
        $this->list->selectedIndexes = [0];
    }

    /**
     * @event buildButton.action
     */
    public function doBuildButtonClick()
    {
        /** @var AbstractBuildType $buildType */
        $buildType = Items::first($this->list->selectedItems);

        if ($buildType) {
            $buildType->onExecute(Ide::get()->getOpenedProject());
        } else {
            UXDialog::show('Данная функция находится в разработке.', 'INFORMATION');
        }
    }
}