<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use php\gui\framework\AbstractForm;
use php\gui\text\UXFont;
use php\gui\UXButton;
use php\gui\UXComboBox;
use php\gui\UXListCell;
use php\util\Flow;

/**
 * Class FontPropertyEditorForm
 * @package ide\forms
 *
 * @property UXComboBox $fontCombobox
 * @property UXComboBox $fontSizeCombobox
 * @property UXComboBox $customFontCombobox
 * @property UXButton $addFontButton
 */
class FontPropertyEditorForm extends AbstractForm
{
    use DialogFormMixin;

    protected function init()
    {
        $this->watch('focused', function ($self, $prop, $old, $new) {
            if (!$new) {
                $this->hide();
            }
        });

        $this->fontCombobox->items->add('System');

        foreach (UXFont::getFamilies() as $family) {
            $this->fontCombobox->items->add($family);
        }

        foreach (Flow::ofRange(6, 30) as $size) {
            $this->fontSizeCombobox->items->add($size);
        }

        $this->fontCombobox->onCellRender(function (UXListCell $cell, $value) {
            $cell->font = UXFont::of($value, $cell->font->size);
            $cell->text = $value;
        });
    }

    /**
     * @event show
     */
    public function actionOpen()
    {
        $font = $this->getResult();

        if ($font instanceof UXFont) {
            $this->fontCombobox->value = $font->family;
            $this->fontSizeCombobox->text = (int) $font->size;
        }
    }

    /**
     * @event applyButton.action
     */
    public function actionApply()
    {
        $this->setResult(UXFont::of($this->fontCombobox->value, $this->fontSizeCombobox->text));
        $this->hide();
    }

    /**
     * @event cancelButton.action
     */
    public function actionCancel()
    {
        $this->setResult(null);
        $this->hide();
    }
}