<?php

namespace ide\project\control;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\editors\CodeEditorX;
use ide\editors\menu\AbstractMenuCommand;
use ide\editors\menu\ContextMenu;
use ide\entity\ProjectSkin;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\Logger;
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
use php\gui\text\UXFont;
use php\gui\UXApplication;
use php\gui\UXFileChooser;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\layout\UXAnchorPane;
use php\gui\UXSeparator;
use php\lang\System;
use php\lib\str;
use php\util\Configuration;
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
        $this->uiSkinName = new UXLabel();
        $icon = ico('brush32');
        UXHBox::setMargin($icon, [0, 5, 0, 0]);


        $menu = new ContextMenu(null, [
            new DesignProjectControlPane_SkinClearCommand($this),
            new DesignProjectControlPane_SkinConvertToTheme($this),
        ]);

        $pane = UiUtils::makeCommandPane([
            $icon,
            $this->uiSkinName,
            '-',
            $menu->makeButton('Выбрать скин', ico('brush16'), function () {
                if ($gui = GuiFrameworkProjectBehaviour::get()) {
                    try {
                        $manager = new SkinManagerForm();
                        if ($manager->showDialog() && $manager->getResult()) {
                            /** @var ProjectSkin $skin */
                            $skin = $manager->getResult();

                            if ($skin->isEmpty()) {
                                $gui->clearSkin();
                            } else {
                                $gui->applySkin($manager->getResult());
                            }

                            $this->refresh();
                        }
                    } catch (\Exception $e) {
                        Logger::exception($e->getMessage(), $e);
                        MessageBoxForm::warning($e->getMessage());
                    }
                }
            }),
            '-',
            SimpleSingleCommand::makeWithText('Сохранить CSS как скин', 'icons/save16.png', function () {
                $dialog = new SkinSaveDialogForm($this->editor->getFile());
                $project = Ide::project();

                $propsFile = $project->getSrcFile('.theme/skin.properties');

                if ($propsFile->exists()) {
                    $config = new Configuration($propsFile);
                    $dialog->nameField->text = $config->get('name', $project->getName());
                    $dialog->descField->text = $config->get('description');
                    $dialog->uidField->text = $config->get('uid', str::replace($dialog->nameField->text, ' ', ''));
                    $dialog->authorField->text = $config->get('author', System::getProperty('user.name'));
                    $dialog->authorSiteField->text = $config->get('authorSite');
                } else {
                    $dialog->uidField->text = str::replace($project->getName(), ' ', '');
                    $dialog->nameField->text = $project->getName();
                    $dialog->authorField->text = System::getProperty('user.name');
                }

                $dialog->showAndWait();
            })
        ]);

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
        if ($this->editor) {
            $this->editor->loadContentToAreaIfModified();
        }

        if ($this->ui) {
            $this->ui->requestFocus();

            uiLater(function () {
                $this->editor->requestFocus();
            });

            $this->uiSkinName->text = '(Скин не выбран)';
            $this->uiSkinName->textColor = 'gray';
            $this->uiSkinName->font = UXFont::of('System', UiUtils::fontSize());

            if ($gui = GuiFrameworkProjectBehaviour::get()) {
                $skin = $gui->getCurrentSkin();

                if ($skin) {
                    $this->uiSkinName->text = $skin->getName();
                    $this->uiSkinName->textColor = 'black';
                    $this->uiSkinName->font = UXFont::of('System', UiUtils::fontSize(), 'BOLD');
                }
            }
        }
        // nop.
    }
}

class DesignProjectControlPane_SkinConvertToTheme extends AbstractMenuCommand
{
    /**
     * @var DesignProjectControlPane
     */
    private $pane;

    /**
     * DesignProjectControlPane_SkinClearCommand constructor.
     * @param DesignProjectControlPane $pane
     */
    public function __construct(DesignProjectControlPane $pane)
    {
        $this->pane = $pane;
    }

    public function getName()
    {
        return "Конвертировать скин в стили проекта";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if (MessageBoxForm::confirm('Все стили проекта будут заменены стилями скина, Вы уверены?')) {
            $gui = GuiFrameworkProjectBehaviour::get();
            $gui->convertSkinToTheme();
            $this->pane->refresh();
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $item->enabled = !!GuiFrameworkProjectBehaviour::get()->getCurrentSkin();
    }
}

class DesignProjectControlPane_SkinClearCommand extends AbstractMenuCommand
{
    /**
     * @var DesignProjectControlPane
     */
    private $pane;

    /**
     * DesignProjectControlPane_SkinClearCommand constructor.
     * @param DesignProjectControlPane $pane
     */
    public function __construct(DesignProjectControlPane $pane)
    {
        $this->pane = $pane;
    }

    public function getName()
    {
        return "Убрать скин с проекта";
    }

    public function getIcon()
    {
        return 'icons/clear16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if ($gui = GuiFrameworkProjectBehaviour::get()) {
            $gui->clearSkin();
            $this->pane->refresh();
        }
    }

    public function onBeforeShow($item, AbstractEditor $editor = null)
    {
        parent::onBeforeShow($item, $editor);

        $item->enabled = !!GuiFrameworkProjectBehaviour::get()->getCurrentSkin();
    }
}