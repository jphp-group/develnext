<?php
namespace ide\forms;

use ide\action\AbstractSimpleActionType;
use ide\action\Action;
use ide\editors\argument\AbstractArgumentEditor;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabel;

/**
 * Class ActionArgumentsDialog
 * @package ide\forms
 *
 * @property UXButton $okButton
 * @property UXButton $cancelButton
 * @property UXLabel $titleLabel
 * @property UXLabel $descriptionLabel
 * @property UXImageView $icon
 *
 * @property UXVBox $box
 */
class ActionArgumentsDialog extends AbstractForm
{
    use DialogFormMixin;

    /**
     * @var AbstractArgumentEditor[]
     */
    protected $argumentEditors = [];

    public function setAction(Action $action)
    {
        $this->box->children->clear();

        /** @var AbstractSimpleActionType $type */
        $type = $action->getType();

        $this->icon->image = Ide::get()->getImage($type->getIcon())->image;
        $this->title =  $this->titleLabel->text = $type->getTitle();
        $this->descriptionLabel->text = $type->getDescription();

        $attributeLabels = $type->attributeLabels();

        $i = 0;
        foreach ($type->attributes() as $name => $value) {
            $label = $attributeLabels[$name];

            if (!$label) {
                $label = $name;
            }

            $editor = $this->addArgument($name, $label, $value);

            if ($i == 0) {
                $editor->requestUiFocus();
            }

            $editor->setValue($action->{$name}, $action->{"$name-type"});

            $i++;
        }

        UXApplication::runLater(function () {
            $height = 0;
            foreach ($this->box->children as $line) {
                $height += $line->height + $this->box->spacing;
            }

            if ($height > 0) {
                $height -= $this->box->spacing;
            }

            $this->height = $height + 180;
        });
    }

    public function addArgument($name, $label, $type)
    {
        $editor = AbstractArgumentEditor::make($type);
        $editor->setUserData($this->userData);

        $this->argumentEditors[$name] = $editor;

        $ui = $editor->makeUi($label);

        if (!$editor->isInline()) {
            $labelUi = new UXLabel("$label:");
        } else {
            $labelUi = null;
        }

        $layout = new UXVBox($labelUi ? [$labelUi, $ui] : [$ui]);
        $layout->spacing = 5;
        $layout->padding = $labelUi ? 3 : 10;

        $layout->paddingLeft = $layout->paddingRight = 10;
        $layout->fillWidth = true;

        $this->box->add($layout);

        return $editor;
    }

    /**
     * @event okButton.action
     */
    public function actionApply()
    {
        $result = [];

        foreach ($this->argumentEditors as $name => $editor) {
            $result[$name] = $editor->getValue();
            $type = $result["$name-type"] = $editor->getValueType();
        }

        $this->setResult($result);
        $this->hide();
    }

    /**
     * @event cancelButton.action
     * @event close
     */
    public function actionClose()
    {
        $this->setResult(null);
        $this->hide();
    }
}