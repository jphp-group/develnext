<?php
namespace ide\forms;

use ide\editors\menu\ContextMenu;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\library\IdeLibraryResource;
use ide\misc\AbstractCommand;
use ide\project\AbstractProjectTemplate;
use ide\systems\ProjectSystem;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXDialog;
use php\gui\UXDirectoryChooser;
use php\gui\UXFileChooser;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTextField;
use php\io\File;
use php\lib\Items;
use php\lib\Str;

/**
 *
 * @property UXImageView $icon
 * @property UXListView $templateList
 * @property UXTextField $pathField
 * @property UXTextField $nameField
 *
 * Class NewProjectForm
 * @package ide\forms
 */
class NewProjectForm extends AbstractForm
{
    use DialogFormMixin;

    /** @var AbstractProjectTemplate[] */
    protected $templates;

    /** @var UXFileChooser */
    protected $directoryChooser;

    /**
     * @var ContextMenu
     */
    protected $contextMenu;

    public function init()
    {
        $this->contextMenu = new ContextMenu();

        $this->contextMenu->addCommand(AbstractCommand::make('Удалить', 'icons/delete16.png', function () {
            $resource = Items::first($this->templateList->selectedItems);

            if ($resource instanceof IdeLibraryResource) {
                $msg = new MessageBoxForm("Вы уверены, что хотите удалить проект {$resource->getName()} из библиотеки?", ['Да, удалить', 'Нет']);

                if ($msg->showDialog() && $msg->getResultIndex() == 0) {
                    Ide::get()->getLibrary()->delete($resource);
                    $this->doShow();
                }
            }
        }));

        $this->directoryChooser = new UXDirectoryChooser();

        $this->icon->image = Ide::get()->getImage('icons/new32.png')->image;
        $this->modality = 'APPLICATION_MODAL';
        $this->title = 'Новый проект';

        $this->pathField->text = Ide::get()->getUserConfigValue('projectDirectory');


        $this->templateList->setCellFactory(function (UXListCell $cell, $template = null) {
            if ($template) {
                if (is_string($template)) {
                    $cell->text = $template . ":";
                    $cell->textColor = UXColor::of('gray');
                    $cell->padding = [5, 10];
                    $cell->paddingTop = 10;
                    $cell->style = '-fx-font-style: italic;';
                    $cell->graphic = null;
                } else {
                    $titleName = new UXLabel($template->getName());
                    $titleName->style = '-fx-font-weight: bold;';

                    $titleDescription = new UXLabel($template->getDescription());
                    $titleDescription->style = '-fx-text-fill: gray;';

                    if (!$titleDescription->text && $template instanceof IdeLibraryResource) {
                        $titleDescription->text = 'Шаблонный проект без описания';
                    }

                    $title = new UXVBox([$titleName, $titleDescription]);
                    $title->spacing = 0;

                    $line = new UXHBox([$template instanceof AbstractProjectTemplate ? Ide::get()->getImage($template->getIcon32()) : ico('programEx32'), $title]);
                    $line->spacing = 7;
                    $line->padding = 5;

                    $cell->text = null;
                    $cell->graphic = $line;
                    $cell->style = '';
                }
            }
        });
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $templates = Ide::get()->getProjectTemplates();
        $this->templates = Items::toArray($templates);

        $this->templateList->items->clear();

        foreach ($templates as $template) {
            $this->templateList->items->add($template);
        }

        $libraryResources = Ide::get()->getLibrary()->getResources('projects');

        if ($libraryResources) {
            $this->templateList->items->add('Библиотека проектов');
        }

        $this->templateList->items->addAll($libraryResources);

        if ($templates) {
            $this->templateList->selectedIndexes = [0];
        }

        $this->nameField->requestFocus();
    }

    /**
     * @event templateList.click-Right
     * @param UXMouseEvent $e
     */
    public function doContextMenu(UXMouseEvent $e)
    {
        $resource = Items::first($this->templateList->selectedItems);

        if ($resource instanceof IdeLibraryResource) {
            $this->contextMenu->getRoot()->show($this, $e->screenX, $e->screenY);
        }
    }

    /**
     * @event pathButton.action
     */
    public function doChoosePath()
    {
        $path = $this->directoryChooser->execute();

        if ($path !== null) {
            $this->pathField->text = $path;

            Ide::get()->setUserConfigValue('projectDirectory', $path);
        }
    }

    /**
     * @event createButton.action
     */
    public function doCreate()
    {
        $template = Items::first($this->templateList->selectedItems);

        if (!$template || !is_object($template)) {
            UXDialog::show('Выберите шаблон для проекта');
            return;
        }

        $path = File::of($this->pathField->text);

        if (!$path->isDirectory()) {
            if (!$path->mkdirs()) {
                UXDialog::show('Невозможно создать папку проектов', 'ERROR');
                return;
            }
        }

        $name = $this->nameField->text;

        if (!$name) {
            UXDialog::show('Введите название для нового проекта', 'ERROR');
            return;
        }

        if ($template instanceof IdeLibraryResource) {
            ProjectSystem::import($template->getPath(), "$path/$name", $name);
        } else {
            $filename = File::of("$path/$name/$name.dnproject");

            if ($filename->isFile()) {
                UXDialog::show('Невозможно создать проект, т.к. проект уже существует.', 'ERROR');
                return;
            }

            if (!$filename->createNewFile(true)) {
                UXDialog::show("Невозможно создать файл проекта по выбранному пути\n -> $filename", 'ERROR');
                return;
            }

            ProjectSystem::close();
            ProjectSystem::create($template, $filename);
        }

        $this->hide();
    }

    /**
     * @event cancelButton.click
     */
    public function doCancel()
    {
        $this->hide();
    }
}