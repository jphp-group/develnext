<?php
namespace ide\forms;

use ide\account\api\ServiceResponse;
use ide\account\ui\NeedAuthPane;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\library\IdeLibraryProjectResource;
use ide\library\IdeLibraryResource;
use ide\project\Project;
use ide\project\ProjectConfig;
use ide\systems\DialogSystem;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\Preloader;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXDirectoryChooser;
use php\gui\UXFileChooser;
use php\gui\UXForm;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTabPane;
use php\gui\UXTextField;
use php\io\File;
use php\lang\Thread;
use php\lib\arr;
use php\lib\fs;
use php\lib\Items;
use php\lib\Str;
use php\time\Time;

/**
 *
 * @property UXImageView $icon
 * @property UXScrollPane $projectList
 * @property UXTextField $pathField
 * @property UXButton $openButton
 * @property UXTabPane $tabPane
 * @property UXListView $libraryList
 * @property UXListView $sharedList
 * @property UXListView $embeddedLibraryList
 * @property UXAnchorPane $sharedPane
 *
 * Class OpenProjectForm
 * @package ide\forms
 */
class OpenProjectForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var FlowListViewDecorator
     */
    protected $projectListHelper;

    protected $_sharedList;

    /**
     * @param null $tab
     */
    public function __construct($tab = null)
    {
        parent::__construct();

        if ($tab) {
            switch ($tab) {
                case 'library':
                    $this->tabPane->selectedIndex = 2;
                    break;
                case 'embeddedLibrary':
                    $this->tabPane->selectedIndex = 1;
                    break;
                case 'shared':
                    $this->tabPane->selectedIndex = 3;
                    break;
            }
        }
    }

    public function init()
    {
        parent::init();

        $cellFactory = function (UXListCell $cell, IdeLibraryProjectResource $resource) {
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
            $openLink->on('click', function () use ($resource, $cell) {
                $cell->listView->selectedIndex = $cell->listView->items->indexOf($resource);
                $this->doCreate(UXEvent::makeMock($cell->listView));
            });
            $actions->add($openLink);

            $deleteLink = new UXHyperlink('Удалить');
            $deleteLink->on('click', function () use ($resource, $cell) {
                $cell->listView->selectedIndex = $cell->listView->items->indexOf($resource);
                $this->doDelete(UXEvent::makeMock($cell->listView));
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
        };
        $this->embeddedLibraryList->setCellFactory($cellFactory);
        $this->libraryList->setCellFactory($cellFactory);

        $this->sharedList->setCellFactory(function (UXListCell $cell, $item) {
            if ($item instanceof ServiceResponse) {
                if ($item->isNotSuccess()) {
                    $cell->text = 'Ошибка, список проектов недоступен.';
                    $cell->textColor = 'gray';
                }
            } else {
                $cell->text = null;
                $cell->graphic = $this->sharedCellFactory($item);
            }
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

        $this->icon->image = Ide::get()->getImage('icons/open32.png')->image;
        $this->modality = 'APPLICATION_MODAL';
        $this->title = 'Открыть проект';

        Ide::accountManager()->bind('login', [$this, 'updateShared']);
        Ide::accountManager()->bind('logout', [$this, 'updateShared']);
    }

    protected function sharedCellFactory(array $item)
    {
        $name = $item['name'] ?: 'Неизвестный проект';

        $titleName = new UXLabel($name);
        $titleName->style = '-fx-font-weight: bold;';

        $titleDescription = new UXLabel("Обновлено: " . (new Time($item['updatedAt']))->toString('dd.MM.yyyy HH:mm'));
        $titleDescription->style = '-fx-text-fill: gray;';

        $actions = new UXHBox();
        $actions->spacing = 7;

        $openLink = new UXHyperlink('Открыть');
        $openLink->on('click', function () use ($item) {
            $this->showPreloader('Подождите ...');
            $form = new SharedProjectDetailForm($item['uid']);

            if ($form->showDialog()) {
                $this->hide();
            }

            $this->hidePreloader();
        });
        $actions->add($openLink);

        $deleteLink = new UXHyperlink('Удалить');
        $deleteLink->on('click', function () use ($item, $name) {
            if (MessageBoxForm::confirmDelete($name)) {
                $response = Ide::service()->projectArchive()->delete($item['id']);

                if ($response->isSuccess()) {
                    Notifications::showProjectIsDeleted();
                    $this->updateShared();
                } else {
                    Logger::warn("Unable to delete project with uid = {$item['uid']}, {$response->toLog()}");
                    Notifications::showProjectIsDeletedFail();
                }
            }
        });
        $actions->add($deleteLink);

        $actions->add(new UXLabel("Просмотров: {$item['viewCount']}"));
        $actions->add(new UXLabel("Скачиваний: {$item['downloadCount']}"));
        $actions->add(new UXLabel("Клонирований: {$item['cloneCount']}"));

        $title = new UXVBox([$titleName, $titleDescription, $actions]);
        $title->spacing = 0;

        $line = new UXHBox([ico('package32'), $title]);
        $line->alignment = 'CENTER_LEFT';
        $line->spacing = 12;
        $line->padding = 5;

        return $line;
    }

    public function update()
    {
        $this->projectListHelper->clear();

        $th = new Thread(function () {
            $projectDirectory = File::of(Ide::get()->getUserConfigValue('projectDirectory'));

            $projects = [];

            foreach ($projectDirectory->findFiles() as $file) {
                if ($file->isDirectory()) {
                    $project = arr::first($file->findFiles(function (File $directory, $name) {
                        return Str::endsWith($name, '.dnproject');
                    }));

                    if ($project) {
                        $projects[] = $project;
                    }
                }
            }

            arr::sort($projects, function (File $a, File $b) {
                if ($a->lastModified() === $b->lastModified()) {
                    return 0;
                }

                return $a->lastModified() > $b->lastModified() ? -1 : 1;
            });

            foreach ($projects as $project) {
                /** @var File $project */
                $config = ProjectConfig::createForFile($project);
                $template = $config->getTemplate();

                uiLater(function () use ($project, $template) {
                    $one = new ImageBox(72, 48);
                    $one->data('file', $project);
                    $one->data('name', fs::pathNoExt($project->getName()));
                    $one->setTitle(fs::pathNoExt($project->getName()));
                    $one->setImage(Ide::get()->getImage($template ? $template->getIcon32() : 'icons/question32.png')->image);

                    $one->on('click', function (UXMouseEvent $e) {
                        $fix = $e;
                        UXApplication::runLater(function () use ($e) {
                            $this->doProjectListClick($e);
                        });
                    });

                    $this->projectListHelper->add($one);
                });
            }

            uiLater(function () use ($projectDirectory) {
                $this->pathField->text = $projectDirectory;
            });
        });

        $th->start();
    }

    public function updateLibrary()
    {
        $this->libraryList->items->clear();
        $this->embeddedLibraryList->items->clear();

        $th = new Thread(function () {
            $libraryResources = Ide::get()->getLibrary()->getResources('projects');

            foreach ($libraryResources as $resource) {
                uiLater(function () use ($resource) {
                    if (!$resource->isEmbedded()) {
                        $this->libraryList->items->add($resource);
                    } else {
                        $this->embeddedLibraryList->items->add($resource);
                    }
                });
            }

            uiLater(function () {
                $this->embeddedLibraryList->selectedIndex = 0;
                $this->libraryList->selectedIndex = 0;
            });
        });

        $th->start();
    }

    public function updateShared()
    {
        waitAsync(600, function () {
            if (Ide::accountManager()->isAuthorized()) {
                if ($pane = $this->sharedPane->lookup('#need_auth')) {
                    $pane->free();
                }

                $this->sharedList->items->clear();

                $preloader = new Preloader($this->sharedPane);
                $preloader->show();

                Ide::service()->projectArchive()->getListAsync(0, 100, function (ServiceResponse $response) use ($preloader) {
                    $preloader->hide();

                    uiLater(function () use ($response) {
                        if ($response->isSuccess()) {
                            // for performance.
                            foreach ($response->data() as $one) {
                                uiLater(function () use ($one) {
                                    $this->sharedList->items->add($one);
                                });
                            }
                        } else {
                            $this->sharedList->items->setAll([$response]);
                        }
                    });
                });
            } else {
                if (!$this->sharedPane->lookup('#need_auth')) {
                    $pane = new NeedAuthPane();
                    $pane->id = 'need_auth';

                    UXAnchorPane::setAnchor($pane, 3);

                    $this->sharedPane->add($pane);
                }
            }
        });
    }

    /**
     * @event showing
     */
    public function doShowing()
    {
        $this->update();
        $this->updateLibrary();
        $this->updateShared();
    }

    /**
     * @event openButton.click
     */
    public function doOpenButtonClick()
    {
        if ($file = DialogSystem::getOpenProject()->execute()) {
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
                $this->showPreloader('Подождите ...');

                waitAsync(100, function () use ($file) {
                    try {
                        ProjectSystem::open($file);
                        $this->hide();
                    } finally {
                        $this->hidePreloader();
                    }
                });
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
        $path = DialogSystem::getProjectsDirectory()->execute();

        if ($path !== null) {
            $this->pathField->text = $path;

            Ide::get()->setUserConfigValue('projectDirectory', $path);
            $this->update();
        }
    }

    /**
     * @event embeddedLibraryList.click-2x
     * @event libraryList.click-2x
     * @param UXEvent $e
     */
    public function doCreate(UXEvent $e)
    {
        /** @var UXListView $listView */
        $listView = $e->sender;

        /** @var IdeLibraryProjectResource $selected */
        $selected = $listView->selectedItem;

        if ($selected) {
            $path = File::of($this->pathField->text);

            if (!$path->isDirectory()) {
                if (!$path->mkdirs()) {
                    UXDialog::show('Невозможно создать папку проектов', 'ERROR');
                    return;
                }
            }

            $name = FileUtils::stripExtension(File::of($selected->getPath())->getName());

            $this->showPreloader('Подождите ...');

            waitAsync(100, function () use ($path, $name, $selected) {
                try {
                    ProjectSystem::import($selected->getPath(), "$path/$name", $name, [$this, 'hide']);
                    $this->hide();
                } finally {
                    $this->hidePreloader();
                }
            });
        }
    }

    public function doDelete(UXEvent $e)
    {
        /** @var UXListView $listView */
        $listView = $e->sender;

        /** @var IdeLibraryProjectResource $selected */
        $selected = $listView->selectedItem;

        if ($selected) {
            if (MessageBoxForm::confirmDelete($selected->getName())) {
                Ide::get()->getLibrary()->delete($selected);
                $this->updateLibrary();
                $listView->selectedIndex = -1;
            }
        }
    }

    public function selectLibraryResource(IdeLibraryResource $resource)
    {
        foreach ([$this->libraryList, $this->embeddedLibraryList] as $list) {
            foreach ($list->items as $i => $it) {
                if (FileUtils::equalNames($resource->getPath(), $it->getPath())) {
                    $list->selectedIndex = $i;
                    return;
                }
            }
        }
    }
}