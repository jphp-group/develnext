<?php
namespace ide\project\behaviours\backup;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\project\behaviours\BackupProjectBehaviour;

class BackupRestoreMenuCommand extends BackupMenuCommand
{
    public function getName()
    {
        return 'Восстановить';
    }

    public function getIcon()
    {
        return 'icons/return16.png';
    }

    public function onBackupExecute(Backup $backup)
    {
        $this->behaviour->restoreFromBackupRequest($backup);
    }
}