<?php

namespace ide\project\behaviours\backup;

use ide\editors\menu\ContextMenu;
use ide\editors\ProjectEditor;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\SimpleSingleCommand;
use ide\project\behaviours\BackupProjectBehaviour;
use ide\project\control\AbstractProjectControlPane;
use ide\systems\FileSystem;
use ide\utils\UiUtils;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\lib\fs;
use php\time\Time;
use php\util\Locale;

class BackupProjectControlPane extends AbstractProjectControlPane
{
    /**
     * @var BackupProjectBehaviour
     */
    private $behaviour;

    /**
     * @var UXListView
     */
    private $uiList;

    /**
     * @var UXListView
     */
    private $uiMasterList;

    /**
     * @var ContextMenu
     */
    private $contextMenu;

    public function __construct(BackupProjectBehaviour $behaviour)
    {
        $this->behaviour = $behaviour;
    }

    public function getName()
    {
        return "Архив проекта";
    }

    public function getDescription()
    {
        return "Резервные копии";
    }

    public function getIcon()
    {
        return 'icons/backup16.png';
    }

    function getMenuCount()
    {
        return sizeof($this->behaviour->getAutoBackups()) + sizeof($this->behaviour->getMasterBackups());
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        $list = $this->uiList = new UXListView();
        $masterList = $this->uiMasterList = new UXListView();

        $cellFactory = function (UXListCell $cell, Backup $backup = null) {
            $cell->text = null;
            $cell->graphic = null;

            if ($backup) {
                $title = new UXLabel($backup->getName());
                $title->style = UiUtils::fontSizeStyle() . "; -fx-font-weight: bold;";
                $createdAt = new Time($backup->getCreatedAt());

                $createdAtText = $createdAt->toString('dd MMM, HH:mm:ss, yyyy г.', Locale::RUSSIAN());

                if ($backup->getMaster()) {
                    $description = new UXLabel($createdAtText .
                        ($backup->getDescription() ? ", {$backup->getDescription()}" : "")
                    );
                } else {
                    $title->text = $createdAtText;
                    $description = new UXLabel($backup->getDescription() . ", " . fs::name($backup->getFilename()));
                }

                if ($backup->isNew()) {
                    $title->style = '-fx-text-fill: blue';
                }

                $description->style = '-fx-text-fill: gray; ' . UiUtils::fontSizeStyle();

                $cell->graphic = new UXHBox([
                    ico('archive32'),
                    new UXVBox([$title, $description], 0)
                ], 10);
                $cell->graphic->padding = 4;
            }
        };
        $masterList->setCellFactory($cellFactory);
        $list->setCellFactory($cellFactory);

        $list->fixedCellSize = $masterList->fixedCellSize = 45;

        $clickHandler = function (UXMouseEvent $e) {
            if ($e->clickCount > 1) {
                $this->behaviour->restoreFromBackupRequest($e->sender->selectedItem);
            }
        };

        $masterList->on('click', $clickHandler);
        $list->on('click', $clickHandler);

        $masterList->height = $masterList->fixedCellSize * 4 + 2;
        UXVBox::setVgrow($list, 'ALWAYS');

        $label1 = new UXLabel("Мастер-копии");
        $label2 = new UXLabel("Автоматические копии");

        $label1->font->bold = $label2->font->bold = true;

        $contextMenu = new ContextMenu(null, $this->createContextCommands($this->uiMasterList));
        $contextMenu->linkTo($this->uiMasterList);

        $contextMenu = new ContextMenu(null, $this->createContextCommands($this->uiList));
        $contextMenu->linkTo($this->uiList);

        return new UXVBox([
            $label1, $this->createMasterActionPane($masterList), $masterList,
            new UXSeparator(),
            $label2, $this->createAutoActionPane($list), $list
        ], 10);
    }

    private function createContextCommands(UXListView $listView)
    {
        $backupGetter = function () use ($listView) {
            return $listView->selectedItem;
        };

        return [
            new BackupRestoreMenuCommand($this->behaviour, $backupGetter),
            new BackupDeleteMenuCommand($this->behaviour, $backupGetter),
        ];
    }

    private function createAutoActionPane(UXListView $listView)
    {
        $backupGetter = function () use ($listView) {
            return $listView->selectedItem;
        };

        $box = UiUtils::makeCommandPane([
            new BackupRestoreMenuCommand($this->behaviour, $backupGetter),
            new BackupDeleteMenuCommand($this->behaviour, $backupGetter),
            '-',
            new BackupCleanAutoCommand($this->behaviour),
            '-',
            new BackupSettingsMenuCommand($this->behaviour)
        ]);
        $box->minHeight = 32;
        $box->spacing = 7;

        foreach ($box->children as $one) {
            if ($one instanceof UXSeparator) {
                $one->padding = [0, 0, 0, 2];
            }
        }

        $box->spacing = 5;

        return $box;
    }

    private function createMasterActionPane(UXListView $listView)
    {
        $backupGetter = function () use ($listView) {
            return $listView->selectedItem;
        };

        $box = UiUtils::makeCommandPane([
            SimpleSingleCommand::makeWithText('Сделать мастер-копию', 'icons/backup16.png', function () {
                $this->behaviour->makeMasterBackupRequest();
            }),
            '-',
            new BackupRestoreMenuCommand($this->behaviour, $backupGetter),
            new BackupDeleteMenuCommand($this->behaviour, $backupGetter),
            '-',
            new BackupCleanMasterCommand($this->behaviour)
        ]);
        $box->minHeight = 32;
        $box->spacing = 5;

        foreach ($box->children as $one) {
            if ($one instanceof UXSeparator) {
                $one->padding = [0, 0, 0, 2];
            }
        }

        return $box;
    }

    /**
     * Refresh ui and pane.
     */
    public function refresh()
    {
        if ($this->uiList) {
            $this->uiList->items->setAll($this->behaviour->getAutoBackups());
        }

        if ($this->uiMasterList) {
            $this->uiMasterList->items->setAll($this->behaviour->getMasterBackups());
        }
    }
}