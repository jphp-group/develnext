<?php

namespace ide\project\control;

use ide\editors\CodeEditor;
use ide\editors\CodeEditorX;
use ide\entity\ProjectSkin;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\SimpleSingleCommand;
use ide\project\behaviours\gui\SkinManagerForm;
use ide\project\behaviours\gui\SkinSaveDialogForm;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use ide\utils\FileUtils;
use ide\utils\StrUtils;
use ide\utils\UiUtils;
use php\gui\designer\UXCssCodeArea;
use php\gui\designer\UXSyntaxTextArea;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXFileChooser;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\layout\UXAnchorPane;
use php\gui\UXSeparator;
use php\lang\System;
use php\lib\str;
use php\util\Regex;

/**
 * @package ide\project\control
 */
class DesignProjectControlPane extends AbstractProjectControlPane
{
    /**
     * @var CodeEditor
     */
    protected $editor;

    /**
     * @var UXLabel
     */
    protected $uiSkinName;

    /**
     * @var bool
     */
    protected $loaded = false;


    public function getName()
    {
        return "Внешний вид";
    }

    public function getDescription()
    {
        return "CSS стиль и дизайн";
    }

    public function getIcon()
    {
        return 'icons/design16.png';
    }

    public function save()
    {
        if ($this->editor) {
            $this->editor->save();
        }
    }

    public function open()
    {
        if ($this->editor) {
            $this->editor->open();
            $this->editor->refreshUi();
        }
    }

    public function load()
    {
        if ($this->editor) {
            $this->editor->load();

            $this->loaded = true;
        }
    }


    protected function makeActionsUi()
    {
        $pane = UiUtils::makeCommandPane([
            '-',
            SimpleSingleCommand::makeWithText('Выбрать скин', 'icons/brush16.png', function () {

                if ($gui = GuiFrameworkProjectBehaviour::get()) {
                    try {
                        $manager = new SkinManagerForm();
                        if ($manager->showDialog() && $manager->getResult()) {
                            $gui->applySkin($manager->getResult());
                            $this->refresh();
                        }
                    } catch (\Exception $e) {
                        MessageBoxForm::warning($e->getMessage());
                    }
                }

            }),
            '-',
            SimpleSingleCommand::makeWithText('Сохранить CSS как скин', 'icons/save16.png', function () {
                $dialog = new SkinSaveDialogForm($this->editor->getFile());
                $project = Ide::project();

                $dialog->uidField->text = str::replace($project->getName(), ' ', '');
                $dialog->nameField->text = $project->getName();
                $dialog->authorField->text = System::getProperty('user.name');

                $dialog->showAndWait();
            })
        ]);

        $this->uiSkinName = new UXLabel();
        $pane->children->insert(0, $this->uiSkinName);
        $icon = ico('brush32');

        UXHBox::setMargin($icon, [0, 5, 0, 0]);

        $pane->children->insert(0, $icon);
        $pane->spacing = 5;

        return $pane;
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        $path = Ide::project()->getSrcFile('.theme/style.fx.css');
        $this->editor = Ide::get()->getFormat($path)->createEditor($path);
        $this->editor->setTabbed(false);
        $this->editor->loadContentToArea();

        $cssEditor = $this->editor->makeUi();

        $ui = new UXVBox([$this->makeActionsUi(), new UXSeparator(), $cssEditor], 5);
        UXVBox::setVgrow($cssEditor, 'ALWAYS');

        return $ui;
    }

    /**
     * Refresh ui and pane.
     */
    public function refresh()
    {
        if ($this->ui) {
            $this->ui->requestFocus();

            uiLater(function () {
                $this->editor->requestFocus();
            });

            $this->uiSkinName->text = '(Скин не выбран)';
            $this->uiSkinName->textColor = 'gray';
            $this->uiSkinName->font->bold = false;

            if ($gui = GuiFrameworkProjectBehaviour::get()) {
                $skin = $gui->getCurrentSkin();

                if ($skin) {
                    $this->uiSkinName->text = $skin->getName();
                    $this->uiSkinName->textColor = 'black';
                    $this->uiSkinName->font->bold = true;
                }
            }
        }
        // nop.
    }
}