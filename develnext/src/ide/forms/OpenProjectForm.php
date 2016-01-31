<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\library\IdeLibraryProjectResource;
use ide\project\Project;
use ide\project\ProjectConfig;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXDirectoryChooser;
use php\gui\UXFileChooser;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTabPane;
use php\gui\UXTextField;
use php\io\File;
use php\lib\Items;
use php\lib\Str;

/**
 *
 * @property UXImageView $icon
 * @property UXScrollPane $projectList
 * @property UXTextField $pathField
 * @property UXButton $openButton
 * @property UXTabPane $tabPane
 * @property UXListView $libraryList
 *
 * Class OpenProjectForm
 * @package ide\forms
 */
class OpenProjectForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /** @var UXDirectoryChooser */
    protected $directoryChooser;

    /** @var UXFileChooser */
    protected static $fileChooser;

    /**
     * @var FlowListViewDecorator
     */
    protected $projectListHelper;

    public function init()
    {
        parent::init();

        $this->libraryList->setCellFactory(function (UXListCell $cell, IdeLibraryProjectResource $resource) {
            $titleName = new UXLabel($resource->getName());
            $titleName->style = '-fx-font-weight: bold;';

            $titleDescription = new UXLabel($resource->getDescription());
            $titleDescription->style = '-fx-text-fill: gray;';

            if (!$titleDescription->text) {
                $titleDescription->text = 'Проект без описания ...';
            }

            $actions = new UXHBox();
            $actions->spacing = 7;

            $openLink = new UXHyperlink('Открыть');
            $openLink->on('click', function () use ($resource) {
                $this->libraryList->selectedIndex = $this->libraryList->items->indexOf($resource);
                $this->doCreate();
            });
            $actions->add($openLink);

            $deleteLink = new UXHyperlink('Удалить');
            $deleteLink->on('click', function () use ($resource) {
                $this->libraryList->selectedIndex = $this->libraryList->items->indexOf($resource);
                $this->doDelete();
            });
            $actions->add($deleteLink);

            $title = new UXVBox([$titleName, $titleDescription, $actions]);
            $title->spacing = 0;

            $line = new UXHBox([ico('archive32'), $title]);
            $line->alignment = 'CENTER_LEFT';
            $line->spacing = 12;
            $line->padding = 5;

            $cell->text = null;
            $cell->graphic = $line;
            $cell->style = '';
        });

        $this->projectListHelper = new FlowListViewDecorator($this->projectList->content);
        $this->projectListHelper->setEmptyListText('Список проектов пуст.');
        $this->projectListHelper->setMultipleSelection(true);
        $this->projectListHelper->on('remove', [$this, 'doRemove']);
        $this->projectListHelper->on('beforeRemove', function ($nodes) {
            $what = [];
            foreach ($nodes as $node) {
                $file = $node->data('file');

                if ($file && $file->exists())  {
                    $what[] = $node->data('name');
                }
            }

            if (!MessageBoxForm::confirmDelete($what)) {
                return true;
            }

            return false;
        });

        $this->directoryChooser = new UXDirectoryChooser();

        if (!self::$fileChooser) {
            self::$fileChooser = new UXFileChooser();
            self::$fileChooser->extensionFilters = [
                ['description' => 'DevelNext проекты и архивы', 'extensions' => ['*.dnproject', '*.zip']]
            ];
            //self::$fileChooser->initialDirectory = Ide::get()->getUserConfigValue('projectDirectory');
        }

        $this->icon->image = Ide::get()->getImage('icons/open32.png')->image;
        $this->modality = 'APPLICATION_MODAL';
        $this->title = 'Открыть проект';
    }

    public function update()
    {
        $this->projectListHelper->clear();

        $projectDirectory = File::of(Ide::get()->getUserConfigValue('projectDirectory'));

        $projects = [];

        foreach ($projectDirectory->findFiles() as $file) {
            if ($file->isDirectory()) {
                $project = Items::first($file->findFiles(function (File $directory, $name) {
                    return Str::endsWith($name, '.dnproject');
                }));

                if ($project) {
                    $projects[] = $project;
                }
            }
        }

        Items::sort($projects, function (File $a, File $b) {
            if ($a->lastModified() === $b->lastModified()) {
                return 0;
            }

            return $a->lastModified() > $b->lastModified() ? -1 : 1;
        });

        foreach ($projects as $project) {
            /** @var File $project */
            $config = ProjectConfig::createForFile($project);
            $template = $config->getTemplate();

            $one = new ImageBox(72, 48);
            $one->data('file', $project);
            $one->data('name', FileUtils::stripExtension($project->getName()));
            $one->setTitle(FileUtils::stripExtension($project->getName()));
            $one->setImage(Ide::get()->getImage($template ? $template->getIcon32() : 'icons/question32.png')->image);

            $one->on('click', function (UXMouseEvent $e) {
                $fix = $e;
                UXApplication::runLater(function () use ($e) {
                    $this->doProjectListClick($e);
                });
            });

            $this->projectListHelper->add($one);
        }

        $this->pathField->text = $projectDirectory;
    }

    public function updateLibrary()
    {
        $this->libraryList->items->clear();

        $libraryResources = Ide::get()->getLibrary()->getResources('projects');

        $this->libraryList->items->addAll($libraryResources);
        $this->libraryList->selectedIndex = 0;
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->update();
        $this->updateLibrary();

        if ($this->projectListHelper->count() == 0) {
            $this->tabPane->selectedIndex = 1;
        }
    }

    /**
     * @event openButton.click
     */
    public function doOpenButtonClick()
    {
        if ($file = self::$fileChooser->execute()) {
            $this->hide();

            UXApplication::runLater(function () use ($file) {
                if (Str::endsWith($file, ".zip")) {
                    ProjectSystem::import($file);
                } else {
                    ProjectSystem::open($file);
                }
            });
        }
    }

    /**
     * @param $nodes
     * @return bool
     */
    public function doRemove(array $nodes)
    {
        foreach ($nodes as $node) {
            $file = $node->data('file');

            if ($file && $file->exists()) {
                $directory = File::of($file)->getParent();

                if (Ide::project()
                    && FileUtils::normalizeName(Ide::project()->getRootDir()) == FileUtils::normalizeName($directory)) {
                    ProjectSystem::closeWithWelcome();
                }

                if (!FileUtils::deleteDirectory($directory)) {
                    Notifications::error('Ошибка удаления', 'Папка проекта была не удалена полностью, возможно она занята другими программами.');
                    $this->update();
                }
            }
        }
    }

    /**
     * @param UXMouseEvent $e
     */
    public function doProjectListClick(UXMouseEvent $e)
    {
        if ($e->clickCount > 1) {
            $node = $this->projectListHelper->getSelectionNode();
            $file = $node ? $node->data('file') : null;

            if ($file && $file->exists()) {
                ProjectSystem::open($file);
                $this->hide();
            } else {
                UXDialog::show('Ошибка открытия проекта', 'ERROR');
            }
        }
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
            $this->update();
        }
    }

    /**
     * @event libraryList.click-2x
     */
    public function doCreate()
    {
        /** @var IdeLibraryProjectResource $selected */
        $selected = $this->libraryList->selectedItem;

        if ($selected) {
            $path = File::of($this->pathField->text);

            if (!$path->isDirectory()) {
                if (!$path->mkdirs()) {
                    UXDialog::show('Невозможно создать папку проектов', 'ERROR');
                    return;
                }
            }

            $name = FileUtils::stripExtension(File::of($selected->getPath())->getName());

            ProjectSystem::import($selected->getPath(), "$path/$name", $name, [$this, 'hide']);
        }
    }

    public function doDelete()
    {
        /** @var IdeLibraryProjectResource $selected */
        $selected = $this->libraryList->selectedItem;

        if ($selected) {
            if (MessageBoxForm::confirmDelete($selected->getName())) {
                Ide::get()->getLibrary()->delete($selected);
                $this->updateLibrary();
                $this->libraryList->selectedIndex = -1;
            }
        }
    }
}