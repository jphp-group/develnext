<?php

namespace ide\project\control;

use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
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
use ide\utils\FileUtils;
use ide\utils\UiUtils;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\lib\fs;

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

        if ($gui = GuiFrameworkProjectBehaviour::get()) {
            $project = Ide::project();

            $skin = $gui->getCurrentSkin();

            if ($project->_skinChecked) return;

            $project->_skinChecked = true;

            if ($skin && $skin->getUid() && !Ide::get()->getLibrary()->getResource('skins', $skin->getUid())) {
                if (MessageBoxForm::confirm("Проект содержит скин '{$skin->getName()}', которого нет в вашей библиотеке, хотите сохранить его в библиотеку?")) {

                    $ideLibrary = Ide::get()->getLibrary();
                    $skinFile = $ideLibrary->getResourceDirectory('skins') . "/{$skin->getUid()}.zip";

                    $cssFile = $project->getSrcFile('.theme/skin/skin.css');

                    $dir = fs::parent($cssFile);
                    $additionalFiles = [];

                    fs::scan($dir, function ($filename) use ($dir, &$additionalFiles, $cssFile) {
                        if (fs::isFile($filename)) {
                            $name = FileUtils::relativePath($dir, $filename);

                            if ($name !== 'skin.json' && $name !== fs::name($cssFile)) {
                                $additionalFiles[$name] = $filename;
                            }
                        }
                    });

                    $skin->saveToZip($cssFile, $skinFile, $additionalFiles);
                    $ideLibrary->updateCategory('skins');

                    if (fs::isFile($skinFile)) {
                        Ide::toast('Скин успешно сохранен в библиотеке скинов');
                    } else {
                        MessageBoxForm::warning('Ошибка сохранения скина');
                    }
                }
            }
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
            '-',
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

    public function getIcon()
    {
        return 'icons/convert16.png';
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
        return "(Без скина)";
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