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
use ide\formats\form\elements\LabelFormElement;
use ide\formats\form\elements\TextFieldFormElement;
use ide\formats\form\tags\AnchorPaneFormElementTag;
use ide\formats\form\tags\ButtonFormElementTag;
use ide\formats\form\tags\CheckboxFormElementTag;
use ide\formats\form\tags\DataFormElementTag;
use ide\formats\form\tags\LabeledFormElementTag;
use ide\formats\form\tags\LabelFormElementTag;
use ide\formats\form\tags\NodeFormElementTag;
use ide\formats\form\tags\TextFieldFormElementTag;
use ide\formats\form\tags\TextInputControlFormElementTag;
use ide\utils\FileUtils;
use php\gui\UXNode;

class GuiFormFormat extends AbstractFormFormat
{
    function __construct()
    {
        $this->requireFormat(new PhpCodeFormat());

        // Element types.
        $this->register(new ButtonFormElement());
        $this->register(new LabelFormElement());
        $this->register(new TextFieldFormElement());
        $this->register(new CheckboxFormElement());

        // Element tags.
        $this->register(new NodeFormElementTag());
        $this->register(new DataFormElementTag());
        $this->register(new LabeledFormElementTag());
        $this->register(new AnchorPaneFormElementTag());
        $this->register(new ButtonFormElementTag());
        $this->register(new LabeledFormElementTag());
        $this->register(new LabelFormElementTag());
        $this->register(new TextInputControlFormElementTag());
        $this->register(new TextFieldFormElementTag());
        $this->register(new CheckboxFormElementTag());

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