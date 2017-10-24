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
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\gui\UXFileChooser;
use php\gui\UXImageView;
use php\gui\UXTextArea;
use php\gui\UXTextField;
use php\lib\fs;

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

        $zip = $skin->saveToZip(
            $this->cssFile,
            $zipFile = $ideLibrary->getResourceDirectory('skins') . "/{$skin->getUid()}.zip"
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
        $dialog->extensionFilters = [['description' => 'Skin Files (*.zip)', 'extensions' => ['*.zip']]];

        if ($file = $dialog->showSaveDialog()) {
            if (fs::ext($file) != 'zip') {
                $file = "$file.zip";
            }

            $skin->saveToZip($this->cssFile, $file);
            $this->toast('Скин успешно сохранен в файл');
        }
    }
}