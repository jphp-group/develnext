<?php
namespace ide\editors\common;

use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;

class FormListEditor extends ObjectListEditor
{
    public function updateUi()
    {
        $this->comboBox->items->clear();

        $this->comboBox->items->add(new ObjectListEditorItem($this->emptyItemText, null, ''));

        $project = Ide::get()->getOpenedProject();

        if ($this->senderCode) {
            $this->comboBox->items->add(new ObjectListEditorItem('Текущая форма', null, $this->senderCode));
        }

        if ($project && $project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
            /** @var GuiFrameworkProjectBehaviour $gui */
            $gui = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

            foreach ($gui->getFormEditors() as $formEditor) {
                $this->comboBox->items->add(new ObjectListEditorItem(
                    $formEditor->getTitle(),
                    Ide::get()->getImage($formEditor->getIcon()),
                    $formEditor->getTitle()
                ));
            }
        }
    }
}