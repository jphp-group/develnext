<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\project\AbstractProjectTemplate;
use ide\systems\ProjectSystem;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
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

    public function init()
    {
        $this->directoryChooser = new UXDirectoryChooser();

        $this->icon->image = Ide::get()->getImage('icons/new32.png')->image;
        $this->modality = 'APPLICATION_MODAL';
        $this->title = 'Новый проект';

        $this->pathField->text = Ide::get()->getUserConfigValue('projectDirectory');

        $this->templateList->setCellFactory(function (UXListCell $cell, AbstractProjectTemplate $template = null) {
            if ($template) {
                $titleName = new UXLabel($template->getName());
                $titleName->style = '-fx-font-weight: bold;';

                $titleDescription = new UXLabel($template->getDescription());
                $titleDescription->style = '-fx-text-fill: gray;';

                $title = new UXVBox([$titleName, $titleDescription]);
                $title->spacing = 0;

                $line = new UXHBox([Ide::get()->getImage($template->getIcon32()), $title]);
                $line->spacing = 7;
                $line->padding = 5;

                $cell->text = null;
                $cell->graphic = $line;
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

        if ($templates) {
            $this->templateList->selectedIndexes = [0];
        }

        $this->nameField->requestFocus();
    }


    /**
     * @event pathButton.click
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
     * @event createButton.click
     */
    public function doCreate()
    {
        $template = $this->templates[$this->templateList->selectedIndexes[0]];

        if (!$template) {
            UXDialog::show('Выберите шаблон для проекта');
        }

        $path = File::of($this->pathField->text);

        if (!$path->isDirectory()) {
            if (!$path->mkdirs()) {
                UXDialog::show('Невозможно создать папку проектов', 'ERROR');
                return;
            }
        }

        $name = $this->nameField->text;

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