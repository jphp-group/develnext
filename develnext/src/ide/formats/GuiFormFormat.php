<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\formats\form\context\CopyMenuCommand;
use ide\formats\form\context\CutMenuCommand;
use ide\formats\form\context\DeleteMenuCommand;
use ide\formats\form\context\LockMenuCommand;
use ide\formats\form\context\PasteMenuCommand;
use ide\formats\form\context\RelocationMenuCommand;
use ide\formats\form\context\SelectAllMenuCommand;
use ide\formats\form\context\ToBackMenuCommand;
use ide\formats\form\context\ToFrontMenuCommand;
use ide\formats\form\context\UpMenuCommand;
use ide\formats\form\elements\ButtonFormElement;
use ide\formats\form\elements\CheckboxFormElement;
use ide\formats\form\elements\CircleFormElement;
use ide\formats\form\elements\ColorPickerFormElement;
use ide\formats\form\elements\ComboBoxFormElement;
use ide\formats\form\elements\FormFormElement;
use ide\formats\form\elements\HyperlinkFormElement;
use ide\formats\form\elements\LabelFormElement;
use ide\formats\form\elements\ListViewFormElement;
use ide\formats\form\elements\PasswordFieldFormElement;
use ide\formats\form\elements\ProgressBarFormElement;
use ide\formats\form\elements\ProgressIndicatorFormElement;
use ide\formats\form\elements\SeparatorFormElement;
use ide\formats\form\elements\TextAreaFormElement;
use ide\formats\form\elements\TextFieldFormElement;
use ide\formats\form\elements\ToggleButtonFormElement;
use ide\formats\form\tags\AnchorPaneFormElementTag;
use ide\formats\form\tags\ButtonFormElementTag;
use ide\formats\form\tags\CheckboxFormElementTag;
use ide\formats\form\tags\ColorPickerFormElementTag;
use ide\formats\form\tags\ComboBoxBaseFormElementTag;
use ide\formats\form\tags\ComboBoxFormElementTag;
use ide\formats\form\tags\DataFormElementTag;
use ide\formats\form\tags\HyperlinkFormElementTag;
use ide\formats\form\tags\LabeledFormElementTag;
use ide\formats\form\tags\LabelFormElementTag;
use ide\formats\form\tags\ListViewFormElementTag;
use ide\formats\form\tags\NodeFormElementTag;
use ide\formats\form\tags\PasswordFieldFormElementTag;
use ide\formats\form\tags\ProgressBarFormElementTag;
use ide\formats\form\tags\ProgressIndicatorFormElementTag;
use ide\formats\form\tags\SeparatorFormElementTag;
use ide\formats\form\tags\TextAreaFormElementTag;
use ide\formats\form\tags\TextFieldFormElementTag;
use ide\formats\form\tags\TextInputControlFormElementTag;
use ide\formats\form\tags\ToggleButtonFormElementTag;
use ide\utils\FileUtils;
use php\gui\UXNode;

class GuiFormFormat extends AbstractFormFormat
{
    function __construct()
    {
        $this->requireFormat(new PhpCodeFormat());

        // Element types.
        $this->register(new FormFormElement());
        $this->register(new ButtonFormElement());
        $this->register(new ToggleButtonFormElement());
        $this->register(new LabelFormElement());
        $this->register(new HyperlinkFormElement());
        $this->register(new TextFieldFormElement());
        $this->register(new PasswordFieldFormElement());
        $this->register(new TextAreaFormElement());
        $this->register(new CheckboxFormElement());
        $this->register(new ComboBoxFormElement());
        $this->register(new ListViewFormElement());
        $this->register(new ProgressBarFormElement());
        $this->register(new ProgressIndicatorFormElement());
        $this->register(new ColorPickerFormElement());
        $this->register(new SeparatorFormElement());

        //$this->register(new CircleFormElement());

        // Element tags.
        $this->register(new NodeFormElementTag());
        $this->register(new DataFormElementTag());
        $this->register(new LabeledFormElementTag());
        $this->register(new AnchorPaneFormElementTag());
        $this->register(new ButtonFormElementTag());
        $this->register(new ToggleButtonFormElementTag());
        $this->register(new LabeledFormElementTag());
        $this->register(new LabelFormElementTag());
        $this->register(new TextInputControlFormElementTag());
        $this->register(new TextFieldFormElementTag());
        $this->register(new PasswordFieldFormElementTag());
        $this->register(new TextAreaFormElementTag());
        $this->register(new CheckboxFormElementTag());
        $this->register(new ProgressBarFormElementTag());
        $this->register(new ProgressIndicatorFormElementTag());
        $this->register(new ComboBoxBaseFormElementTag());
        $this->register(new ComboBoxFormElementTag());
        $this->register(new ListViewFormElementTag());
        $this->register(new HyperlinkFormElementTag());
        $this->register(new SeparatorFormElementTag());
        $this->register(new ColorPickerFormElementTag());

        // Context Menu.
        $this->register(new SelectAllMenuCommand());
        $this->register(new CutMenuCommand());
        $this->register(new CopyMenuCommand());
        $this->register(new PasteMenuCommand());
        $this->register(new DeleteMenuCommand());
        $this->register(new ToFrontMenuCommand());
        $this->register(new ToBackMenuCommand());
        $this->register(new LockMenuCommand());

        $this->registerRelocationCommands();

        $this->registerDone();
    }

    public function getIcon()
    {
        return 'icons/window16.png';
    }

    public function getTitle($path)
    {
        return FileUtils::stripExtension(parent::getTitle($path));
    }

    protected function registerRelocationCommands()
    {
        $this->register(new RelocationMenuCommand('Up', function (UXNode $node, $size) {
            $node->y -= $size;
        }));

        $this->register(new RelocationMenuCommand('Down', function (UXNode $node, $size) {
            $node->y += $size;
        }));

        $this->register(new RelocationMenuCommand('Left', function (UXNode $node, $size) {
            $node->x -= $size;
        }));

        $this->register(new RelocationMenuCommand('Right', function (UXNode $node, $size) {
            $node->x += $size;
        }));
    }

    /**
     * @param $file
     *
     * @return AbstractEditor
     */
    public function createEditor($file)
    {
        return new FormEditor($file, new GuiFormDumper($this->formElementTags));
    }
}