<?php
namespace ide\project\behaviours\gui;

use ide\entity\ProjectSkin;
use ide\forms\AbstractIdeForm;
use ide\forms\MessageBoxForm;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\library\IdeLibrary;
use ide\library\IdeLibrarySkinResource;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use ide\utils\FileUtils;
use php\gui\UXFileChooser;
use php\gui\UXImageView;
use php\gui\UXTextArea;
use php\gui\UXTextField;
use php\io\IOException;
use php\lang\System;
use php\lib\fs;
use php\lib\str;

/**
 * Class SkinSaveDialogForm
 * @package ide\project\behaviours\gui
 *
 * @property UXImageView $icon
 * @property UXTextField $nameField
 * @property UXTextField $uidField
 * @property UXTextArea $descField
 * @property UXTextField $authorField
 * @property UXTextField $authorSiteField
 *
 */
class SkinSaveDialogForm extends AbstractIdeForm
{
    use SavableFormMixin;

    private $cssFile;

    /**
     * SkinSaveDialogForm constructor.
     * @param string $cssFile
     */
    public function __construct(string $cssFile)
    {
        parent::__construct();

        $this->cssFile = $cssFile;
    }


    public function init()
    {
        parent::init();

        $this->icon->image = ico('saveBrush32')->image;
    }

    private function makeSkin(): ProjectSkin
    {
        $skin = new ProjectSkin();
        $skin->setUid($this->uidField->text);
        $skin->setName($this->nameField->text);
        $skin->setDescription($this->descField->text);
        $skin->setAuthor($this->authorField->text);
        $skin->setAuthorSite($this->authorSiteField->text);
        $skin->setScopes(['gui', GuiFrameworkProjectBehaviour::class]);

        return $skin;
    }

    /**
     * @event close
     */
    public function onClose()
    {
        try {
            $this->makeSkin()->saveToFile(fs::pathNoExt($this->cssFile) . ".json");
        } catch (IOException $e) {
            Logger::warn("Unable to save skin.json, {$e->getMessage()}");
        }
    }

    /**
     * @event showing
     */
    public function onShowing()
    {
        $skin = new ProjectSkin();

        $file = fs::parent($this->cssFile) . "/skin.json";

        if (fs::exists($file)) {
            try {
                $skin->loadFromFile($file);
            } catch (IOException $e) {
                Logger::warn("Unable to load skin.json, {$e->getMessage()}");
            }
        }

        $project = Ide::project();

        if ($project) {
            if (!$skin->getUid()) {
                $skin->setUid(str::replace($project->getName(), ' ', ''));
            }

            if (!$skin->getName()) {
                $skin->setName($project->getName());
            }

            if (!$skin->getAuthor()) {
                $skin->setAuthor(System::getProperty('user.name'));
            }
        }

        $this->uidField->text = $skin->getUid();
        $this->nameField->text = $skin->getName();
        $this->descField->text = $skin->getDescription();
        $this->authorField->text = $skin->getAuthor();
        $this->authorSiteField->text = $skin->getAuthorSite();
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel(): void
    {
        $this->hide();
    }

    /**
     * @event saveButton.action
     */
    public function doSave(): void
    {
        $skin = $this->makeSkin();
        $ideLibrary = Ide::get()->getLibrary();

        $skinFile = $ideLibrary->getResourceDirectory('skins') . "/{$skin->getUid()}.zip";

        if (fs::isFile($skinFile)) {
            if (!MessageBoxForm::confirm("Скин с ID '{$skin->getUid()}' уже существует в библиотеке, хотите заменить его?")) {
                return;
            }

            fs::delete($skinFile);
        }

        $zip = $skin->saveToZip(
            $this->cssFile,
            $zipFile = $skinFile
        );

        if (fs::isFile($zipFile)) {
            $ideLibrary->updateCategory('skins');

            $this->hide();
            Ide::toast('Скин успешно сохранен в библиотеке скинов');
        } else {
            MessageBoxForm::warning('Ошибка сохранения скина');
        }
    }

    /**
     * @event saveFileButton.action
     */
    public function doFileSave(): void
    {
        $skin = $this->makeSkin();

        $dialog = new UXFileChooser();
        $dialog->initialFileName = $skin->getUid() . ".zip";
        $dialog->extensionFilters = [['description' => 'Skin Files (*.zip)', 'extensions' => ['*.zip']]];

        if ($file = $dialog->showSaveDialog()) {
            if (fs::ext($file) != 'zip') {
                $file = "$file.zip";
            }

            $dir = fs::parent($this->cssFile);
            $additionalFiles = [];

            fs::scan($dir, function ($filename) use ($dir, &$additionalFiles) {
                if (fs::isFile($filename)) {
                    $name = FileUtils::relativePath($dir, $filename);

                    if (!str::startsWith($name, 'skin/') && $name !== 'skin.json' && $name !== fs::name($this->cssFile)) {
                        $additionalFiles[$name] = $filename;
                    }
                }
            });

            $skin->saveToZip($this->cssFile, $file, $additionalFiles);
            $this->toast('Скин успешно сохранен в файл');
        }
    }
}