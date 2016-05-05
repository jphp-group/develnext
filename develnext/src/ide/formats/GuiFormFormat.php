<?php
namespace ide\formats;

use Files;
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
use ide\formats\form\elements\AnchorPaneFormElement;
use ide\formats\form\elements\ButtonFormElement;
use ide\formats\form\elements\CheckboxFormElement;
use ide\formats\form\elements\CircleFormElement;
use ide\formats\form\elements\ColorPickerFormElement;
use ide\formats\form\elements\ComboBoxFormElement;
use ide\formats\form\elements\DatePickerFormElement;
use ide\formats\form\elements\EllipseFormElement;
use ide\formats\form\elements\FlatButtonFormElement;
use ide\formats\form\elements\FormFormElement;
use ide\formats\form\elements\GameBackgroundFormElement;
use ide\formats\form\elements\GameObjectFormElement;
use ide\formats\form\elements\GamePaneFormElement;
use ide\formats\form\elements\HBoxFormElement;
use ide\formats\form\elements\HexahedronFormElement;
use ide\formats\form\elements\HyperlinkFormElement;
use ide\formats\form\elements\ImageViewFormElement;
use ide\formats\form\elements\LabelFormElement;
use ide\formats\form\elements\ListViewFormElement;
use ide\formats\form\elements\MaskTextFieldFormElement;
use ide\formats\form\elements\PaginationFormElement;
use ide\formats\form\elements\PanelFormElement;
use ide\formats\form\elements\PasswordFieldFormElement;
use ide\formats\form\elements\ProgressBarFormElement;
use ide\formats\form\elements\ProgressIndicatorFormElement;
use ide\formats\form\elements\RadioGroupPaneFormElement;
use ide\formats\form\elements\RectangleFormElement;
use ide\formats\form\elements\RhombusFormElement;
use ide\formats\form\elements\ScrollPaneFormElement;
use ide\formats\form\elements\SeparatorFormElement;
use ide\formats\form\elements\SliderFormElement;
use ide\formats\form\elements\SpriteViewFormElement;
use ide\formats\form\elements\TableViewFormElement;
use ide\formats\form\elements\TabPaneFormElement;
use ide\formats\form\elements\TextAreaFormElement;
use ide\formats\form\elements\TextFieldFormElement;
use ide\formats\form\elements\TitledPaneFormElement;
use ide\formats\form\elements\ToggleButtonFormElement;
use ide\formats\form\elements\WebViewFormElement;
use ide\formats\form\tags\AnchorPaneFormElementTag;
use ide\formats\form\tags\ButtonFormElementTag;
use ide\formats\form\tags\CheckboxFormElementTag;
use ide\formats\form\tags\CircleFormElementTag;
use ide\formats\form\tags\ColorPickerFormElementTag;
use ide\formats\form\tags\ComboBoxBaseFormElementTag;
use ide\formats\form\tags\ComboBoxFormElementTag;
use ide\formats\form\tags\DataFormElementTag;
use ide\formats\form\tags\DatePickerFormElementTag;
use ide\formats\form\tags\EllipseFormElementTag;
use ide\formats\form\tags\FlatButtonFormElementTag;
use ide\formats\form\tags\GameBackgroundFormElementTag;
use ide\formats\form\tags\GamePaneFormElementTag;
use ide\formats\form\tags\HyperlinkFormElementTag;
use ide\formats\form\tags\ImageViewFormElementTag;
use ide\formats\form\tags\LabeledFormElementTag;
use ide\formats\form\tags\LabelFormElementTag;
use ide\formats\form\tags\ListViewFormElementTag;
use ide\formats\form\tags\MaskTextFieldFormElementTag;
use ide\formats\form\tags\NodeFormElementTag;
use ide\formats\form\tags\PaginationFormElementTag;
use ide\formats\form\tags\PanelFormElementTag;
use ide\formats\form\tags\PasswordFieldFormElementTag;
use ide\formats\form\tags\PolygonFormElementTag;
use ide\formats\form\tags\ProgressBarFormElementTag;
use ide\formats\form\tags\ProgressIndicatorFormElementTag;
use ide\formats\form\tags\RadioGroupPaneFormElementTag;
use ide\formats\form\tags\RectangleFormElementTag;
use ide\formats\form\tags\ScrollPaneFormElementTag;
use ide\formats\form\tags\SeparatorFormElementTag;
use ide\formats\form\tags\ShapeFormElementTag;
use ide\formats\form\tags\SliderFormElementTag;
use ide\formats\form\tags\SpriteViewFormElementTag;
use ide\formats\form\tags\TableViewFormElementTag;
use ide\formats\form\tags\TabPaneFormElementTag;
use ide\formats\form\tags\TextAreaFormElementTag;
use ide\formats\form\tags\TextFieldFormElementTag;
use ide\formats\form\tags\TextInputControlFormElementTag;
use ide\formats\form\tags\TitledPaneFormElementTag;
use ide\formats\form\tags\ToggleButtonFormElementTag;
use ide\formats\form\tags\WebViewFormElementTag;
use ide\forms\SetMainFormForm;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\systems\FileSystem;
use ide\systems\RefactorSystem;
use ide\utils\FileUtils;
use php\gui\UXNode;
use php\io\File;
use php\lib\fs;

class GuiFormFormat extends AbstractFormFormat
{
    const REFACTOR_ELEMENT_ID_TYPE = 'GUI_FORM_FORMAT_ELEMENT_ID';

    function __construct()
    {
        $this->requireFormat(new PhpCodeFormat());

        // Element types.
        $this->register(new GamePaneFormElement());
        $this->register(new SpriteViewFormElement());
        $this->register(new GameBackgroundFormElement());
        //$this->register(new GameObjectFormElement());

        $this->register(new AnchorPaneFormElement());
        $this->register(new FormFormElement());
        $this->register(new ButtonFormElement());
        $this->register(new FlatButtonFormElement());
        $this->register(new ToggleButtonFormElement());
        $this->register(new LabelFormElement());
        $this->register(new HyperlinkFormElement());
        $this->register(new TextFieldFormElement());
        $this->register(new PasswordFieldFormElement());
        $this->register(new MaskTextFieldFormElement());
        $this->register(new DatePickerFormElement());
        $this->register(new TextAreaFormElement());
        $this->register(new CheckboxFormElement());
        $this->register(new ComboBoxFormElement());
        $this->register(new ListViewFormElement());
        $this->register(new ImageViewFormElement());

        $this->register(new ProgressBarFormElement());
        $this->register(new SliderFormElement());
        $this->register(new ColorPickerFormElement());
        $this->register(new ProgressIndicatorFormElement());
        $this->register(new SeparatorFormElement());
        $this->register(new WebViewFormElement());
        $this->register(new TableViewFormElement());
        $this->register(new PaginationFormElement());
        $this->register(new RadioGroupPaneFormElement());

        $this->register(new PanelFormElement());
        $this->register(new TitledPaneFormElement());
        $this->register(new ScrollPaneFormElement());
        $this->register(new TabPaneFormElement());

        $this->register(new RectangleFormElement());
        $this->register(new CircleFormElement());
        $this->register(new EllipseFormElement());
        $this->register(new RhombusFormElement());
        $this->register(new HexahedronFormElement());

        // Element tags.
        $this->register(new NodeFormElementTag());
        $this->register(new DataFormElementTag());
        $this->register(new LabeledFormElementTag());
        $this->register(new AnchorPaneFormElementTag());
        $this->register(new ButtonFormElementTag());
        $this->register(new FlatButtonFormElementTag());
        $this->register(new ToggleButtonFormElementTag());
        $this->register(new LabeledFormElementTag());
        $this->register(new LabelFormElementTag());
        $this->register(new TextInputControlFormElementTag());
        $this->register(new TextFieldFormElementTag());
        $this->register(new MaskTextFieldFormElementTag());
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
        $this->register(new SliderFormElementTag());
        $this->register(new ImageViewFormElementTag());
        $this->register(new DatePickerFormElementTag());
        $this->register(new WebViewFormElementTag());

        $this->register(new TitledPaneFormElementTag());
        $this->register(new TabPaneFormElementTag());
        $this->register(new PanelFormElementTag());

        $this->register(new ShapeFormElementTag());
        $this->register(new RectangleFormElementTag());
        $this->register(new EllipseFormElementTag());
        $this->register(new CircleFormElementTag());
        $this->register(new PolygonFormElementTag());
        $this->register(new SpriteViewFormElementTag());
        $this->register(new ScrollPaneFormElementTag());
        $this->register(new GamePaneFormElementTag());
        $this->register(new GameBackgroundFormElementTag());
        $this->register(new PaginationFormElementTag());
        $this->register(new TableViewFormElementTag());
        $this->register(new RadioGroupPaneFormElementTag());

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
        $this->registerRefactor();

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
        $this->register(new RelocationMenuCommand('Up', function (UXNode $node, $sizeX, $sizeY) {
            $node->y -= $sizeY;
        }));

        $this->register(new RelocationMenuCommand('Down', function (UXNode $node, $sizeX, $sizeY) {
            $node->y += $sizeY;
        }));

        $this->register(new RelocationMenuCommand('Left', function (UXNode $node, $sizeX) {
            $node->x -= $sizeX;
        }));

        $this->register(new RelocationMenuCommand('Right', function (UXNode $node, $sizeX) {
            $node->x += $sizeX;
        }));
    }

    public function delete($path, $silent = false)
    {
        parent::delete($path);

        $name = FileUtils::stripExtension(File::of($path)->getName());

        if (!$silent) {
            if ($behaviour = GuiFrameworkProjectBehaviour::get()) {
                if ($behaviour->getMainForm() == $name) {
                    $dialog = new SetMainFormForm();
                    $dialog->setExcludedForms([$name]);
                    $dialog->showDialog();

                    $behaviour->setMainForm($dialog->getResult(), false);
                }
            }
        }

        $parent = File::of($path)->getParent();

        $path = $parent . '/../app/forms/';

        fs::delete("$parent/$name.conf");

        fs::delete("$path/$name.php");
        fs::delete("$path/$name.php.source");
        fs::delete("$path/$name.php.axml");
        fs::delete("$path/$name.behaviour");
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

    private function registerRefactor()
    {
        RefactorSystem::onRename(self::REFACTOR_ELEMENT_ID_TYPE, function ($target, $newId) {
            $editor = FileSystem::getSelectedEditor();

            if ($editor instanceof FormEditor) {
                $oldId = $editor->getNodeId($target);
                $result = $editor->changeNodeId($target, $newId);

                if ($result == '') {
                    $gui = GuiFrameworkProjectBehaviour::get();

                    if ($gui) {
                        foreach ($gui->getFormEditors() as $it) {
                            if ($editor === $it) {
                                continue;
                            }

                            $factoryName = $editor->getTitle();

                            if ($count = $it->updateClonesForNewType("$factoryName.$oldId", "$factoryName.$newId")) {
                                Logger::debug("Rename prototypes in '$factoryName', count = $count");
                                $it->save();
                            }
                        }
                    }
                } else {
                    Logger::info("Unable to rename to $newId, result = $result");
                }

                return $result;
            }
        });
    }
}