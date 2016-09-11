<?php
namespace ide\editors\common;
use Dialog;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\systems\DialogSystem;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use php\gui\layout\UXVBox;
use php\gui\layout\UXHBox;
use php\gui\UXCell;
use php\gui\UXComboBox;
use php\gui\UXButton;
use php\gui\UXFileChooser;
use php\gui\UXImage;
use php\gui\UXListCell;
use php\io\File;
use php\lib\str;
use php\util\Flow;

/**
 * Class FileListEditor
 * @package ide\editors\common
 */
class FileListEditor extends UXVBox
{
    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var array
     */
    protected $extensions = [];

    /**
     * @var array
     */
    protected $originExtensions = [];

    /**
     * @var bool
     */
    protected $recursive;

    /**
     * @var mixed
     */
    protected $icon;

    /**
     * @var UXHBox
     */
    protected $ui;

    /**
     * @var UXComboBox
     */
    protected $uiCombobox;

    /**
     * @var UXButton
     */
    protected $uiAddButton;

    /**
     * @var UXButton
     */
    protected $uiRemoveButton;

    /**
     * @var UXFileChooser
     */
    protected $uiDialog;

    /**
     * @var string
     */
    private $extensionDescription;

    /**
     * FileListEditor constructor.
     * @param string $baseDir
     * @param $extensionDescription
     * @param array $extensions
     * @param bool $recursive
     */
    public function __construct($baseDir, $extensionDescription, array $extensions, $recursive = true)
    {
        parent::__construct();

        $this->baseDir = $baseDir;
        $this->extensionDescription = $extensionDescription;
        $this->originExtensions = Flow::of($extensions)->map(function ($it) { return str::lower($it); })->toArray();;
        $this->extensions = Flow::of($extensions)->map(function ($it) { return "*." . str::lower($it); })->toArray();
        $this->recursive = $recursive;

        $this->init();

        $this->uiAddButton->on('action', [$this, 'doAdd']);
        $this->uiRemoveButton->on('action', [$this, 'doRemove']);
        $this->uiCombobox->on('action', [$this, 'doSelect']);

        $this->uiDialog = DialogSystem::getAnyFile();

        $this->update();
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    protected function init()
    {
        $combobox = new UXComboBox();
        $combobox->maxWidth = 9999;
        UXHBox::setHgrow($combobox, 'ALWAYS');

        $addButton = new UXButton();
        $addButton->classes->add('icon-add');

        $removeButton = new UXButton();
        $removeButton->enabled = false;
        $removeButton->classes->add('icon-trash2');

        $ui = new UXHBox([$combobox, $addButton, $removeButton]);
        $ui->spacing = 5;

        $this->add($ui);

        $this->ui = $ui;
        $this->uiCombobox = $combobox;
        $this->uiAddButton = $addButton;
        $this->uiRemoveButton = $removeButton;

        $cellRender = function (UXListCell $cell, $item) {
            if ($item instanceof File) {
                $cell->text = $item->getName();
                $cell->graphic = Ide::get()->getImage($this->icon);
            } else {
                $cell->text = $item;
                $cell->graphic = null;
            }
        };

        $combobox->onCellRender($cellRender);
        $combobox->onButtonRender($cellRender);
    }

    public function doSelect()
    {
        uiLater(function () {
            $this->uiRemoveButton->enabled = $this->uiCombobox->selected instanceof File;
        });
    }

    public function doAdd()
    {
        $this->uiDialog->extensionFilters = [[
            'description' => $this->extensionDescription . " (" . str::join($this->extensions, ', ') . ")",
            'extensions' => $this->extensions
        ]];

        if ($file = $this->uiDialog->execute()) {
            $name = $file->getName();

            retry:
            $newFile = File::of("$this->baseDir/$name");

            if ($newFile->exists()) {
                $message = new MessageBoxForm("Файл с названием '$name' уже загружен, хотите заменить его новым файлом?",
                    ['Да, заменить', 'Нет, изменить название', 'Отмена']);

                if ($message->showDialog()) {
                    switch ($message->getResultIndex()) {
                        case 1:
                            $newName = Dialog::input("Новое название", FileUtils::stripExtension($name));
                            if (!$newName) {
                                return;
                            } else {
                                $name = $newName . "." . FileUtils::getExtension($name);
                                goto retry;
                            }
                        case 2:
                            return;
                    }
                }
            }

            if (FileUtils::copyFile($file, $newFile) == -1) {
                Notifications::errorCopyFile($file);
            }

            $this->update();
            $this->setValue($newFile);
        }
    }

    public function doRemove()
    {
        if ($this->uiCombobox->selected instanceof File) {
            $file = File::of($this->uiCombobox->selected);

            if (MessageBoxForm::confirmDelete($file->getName())) {
                if (!$file->delete()) {
                    Notifications::errorDeleteFile($file);
                } else {
                    $this->update();
                }
            }
        }
    }

    public function update()
    {
        if ($this->recursive) {
            $files = [];

            FileUtils::scan($this->baseDir, function ($filename) use (&$files) {
                $files[] = new File($filename);
            });
        } else {
            $files = File::of($this->baseDir)->findFiles();
        }

        $selected = $this->uiCombobox->selected;
        $selectedIndex = 0;

        $this->uiCombobox->items->clear();
        $this->uiCombobox->items->add('[Пусто]');

        foreach ($files as $file) {
            if ($file->isDirectory() || $file->isHidden()) continue;

            if (!in_array(str::lower(FileUtils::getExtension($file)), $this->originExtensions)) {
                continue;
            }

            if ($selected && FileUtils::normalizeName($file) == FileUtils::normalizeName($selected)) {
                $selectedIndex = $this->uiCombobox->items->count;
            }

            $this->uiCombobox->items->add($file);
        }

        $this->uiCombobox->selectedIndex = $selectedIndex;
    }

    /**
     * @return string
     */
    public function getRelativeValue()
    {
        $value = $this->getValue();

        if (!$value) {
            return $value;
        }

        return FileUtils::relativePath($this->baseDir, $value);
    }

    /**
     * @return File|null
     */
    public function getValue()
    {
        if ($this->uiCombobox->selected instanceof File) {
            return $this->uiCombobox->selected;
        }

        return null;
    }

    /**
     * @param $value
     */
    public function setRelativeValue($value)
    {
        if ($value == null) {
            $this->uiCombobox->selectedIndex = 0;
            return;
        }

        $file = new File($this->baseDir, "/$value");

        $selectedIndex = 0;

        foreach ($this->uiCombobox->items as $i => $it) {
            if ($it instanceof File && FileUtils::normalizeName($it) == FileUtils::normalizeName($file)) {
                $selectedIndex = $i;
                break;
            }
        }

        $this->uiCombobox->selectedIndex = $selectedIndex;
        $this->uiRemoveButton->enabled = $selectedIndex > 0;
    }

    public function setValue($newFile)
    {
        $this->setRelativeValue($newFile ? FileUtils::relativePath($this->baseDir, $newFile) : null);
    }
}