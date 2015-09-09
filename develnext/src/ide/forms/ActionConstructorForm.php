<?php
namespace ide\forms;

use ide\action\Action;
use ide\action\ActionEditor;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;

/**
 * Class ActionConstructorForm
 * @package ide\forms
 *
 * @property UXListView $list
 */
class ActionConstructorForm extends AbstractForm
{
    /**
     * @var ActionEditor
     */
    protected $editor;

    protected function init()
    {
        parent::init();

        $this->list->setCellFactory([$this, 'listCellFactory']);
        $this->hintLabel->mouseTransparent = true;
    }

    protected function listCellFactory(UXListCell $cell, Action $action = null, $empty)
    {
        if ($action) {
            $titleName = new UXLabel($action->getTitle());
            $titleName->style = '-fx-font-weight: bold;';

            $titleDescription = new UXLabel($action->getDescription());
            $titleDescription->style = '-fx-text-fill: gray;';

            $title = new UXVBox([$titleName, $titleDescription]);
            $title->spacing = 0;

            $image = Ide::get()->getImage($action->getIcon());

            if (!$image) {
                $image = Ide::get()->getImage('icons/blocks16.png');
            }

            $line = new UXHBox([$image, $title]);
            $line->spacing = 7;
            $line->padding = 5;
            $line->alignment = 'CENTER_LEFT';

            $cell->text = null;
            $cell->graphic = $line;
        }
    }

    public function updateList()
    {
       $this->hintLabel->visible = !$this->list->items->count;
    }

    public function showAndWait(ActionEditor $editor = null, $class = null, $method = null)
    {
        $this->editor = $editor;
        $this->editor->load();

        $actions = $editor->findMethod($class, $method);

        $this->list->items->clear();
        $this->list->items->addAll($actions);

        $this->updateList();

        parent::showAndWait();
    }

    /**
     * @event saveButton.action
     */
    public function actionSave()
    {
        $this->editor->save();
        $this->hide();
    }
}