<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\project\ProjectConfig;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
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
 * @property UXScrollPane $projectList
 * @property UXTextField $pathField
 * @property UXButton $openButton
 *
 * Class OpenProjectForm
 * @package ide\forms
 */
class OpenProjectForm extends AbstractIdeForm
{
    use DialogFormMixin;

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

        $this->projectListHelper = new FlowListViewDecorator($this->projectList->content);
        $this->projectListHelper->setEmptyListText('Список проектов пуст.');
        $this->projectListHelper->setMultipleSelection(false);

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

    /**
     * @event show
     */
    public function doShow()
    {
        $this->update();
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
     * @param UXMouseEvent $e
     * @event projectList.click
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
}