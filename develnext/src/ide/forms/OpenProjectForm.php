<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use ide\project\ProjectConfig;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\utils\FileUtils;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
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
 * @property UXListView $projectList
 * @property UXTextField $pathField
 * @property UXButton $openButton
 *
 * Class OpenProjectForm
 * @package ide\forms
 */
class OpenProjectForm extends AbstractForm
{
    use DialogFormMixin;

    /** @var UXDirectoryChooser */
    protected $directoryChooser;

    /** @var UXFileChooser */
    protected static $fileChooser;

    public function init()
    {
        $this->directoryChooser = new UXDirectoryChooser();

        if (!self::$fileChooser) {
            self::$fileChooser = new UXFileChooser();
            self::$fileChooser->extensionFilters = [
                ['description' => 'DevelNext Projects', 'extensions' => ['*.dnproject']]
            ];
            self::$fileChooser->initialDirectory = Ide::get()->getUserConfigValue('projectDirectory');
        }

        $this->icon->image = Ide::get()->getImage('icons/open32.png')->image;
        $this->modality = 'APPLICATION_MODAL';
        $this->title = 'Открыть проект';

        $this->projectList->style = '-fx-cell-hover-color: silver';

        $this->projectList->setCellFactory(function (UXListCell $cell, File $file = null) {
            if ($file) {
                $config = ProjectConfig::createForFile($file);
                $template = $config->getTemplate();

                $titleName = new UXLabel(FileUtils::stripExtension($file->getName()));
                $titleName->style = '-fx-font-weight: bold;';

                $titleDescription = new UXLabel($file);
                $titleDescription->style = '-fx-text-fill: gray;';

                $title = new UXVBox([$titleName, $titleDescription]);
                $title->spacing = 0;

                $list = [];

                $list[] = Ide::get()->getImage($template ? $template->getIcon32() : 'icons/question32.png');
                $list[] = $title;

                $line = new UXHBox($list);

                $line->spacing = 7;
                $line->padding = 5;

                $cell->text = null;
                $cell->graphic = $line;
            }
        });
    }

    public function update()
    {
        $this->projectList->items->clear();

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

        $this->projectList->items->addAll($projects);

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
            ProjectSystem::open($file);
        }
    }

    /**
     * @param UXMouseEvent $e
     * @event projectList.click
     */
    public function doProjectListClick(UXMouseEvent $e)
    {
        if ($e->clickCount > 1) {
            $file = $this->projectList->focusedItem;

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