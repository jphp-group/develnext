<?php
namespace ide\project\behaviours\backup;


use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use ide\project\behaviours\BackupProjectBehaviour;

class BackupCreateMasterCommand extends AbstractCommand
{
    /**
     * @var BackupProjectBehaviour
     */
    private $behavior;

    /**
     * BackupCreateMasterCommand constructor.
     * @param BackupProjectBehaviour $behavior
     */
    public function __construct(BackupProjectBehaviour $behavior)
    {
        $this->behavior = $behavior;
    }

    public function getIcon()
    {
        return 'icons/backup16.png';
    }

    public function getCategory()
    {
        return 'backup';
    }

    public function getName()
    {
        return 'Создать мастер-копию';
    }

    public function getAccelerator()
    {
        return 'Ctrl + Alt + S';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->behavior->makeMasterBackupRequest();
    }
}