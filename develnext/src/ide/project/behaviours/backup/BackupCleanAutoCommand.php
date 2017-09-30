<?php
namespace ide\project\behaviours\backup;

use ide\editors\AbstractEditor;
use ide\misc\AbstractCommand;
use ide\project\behaviours\BackupProjectBehaviour;

class BackupCleanAutoCommand extends AbstractCommand
{
    /**
     * @var BackupProjectBehaviour
     */
    private $behaviour;

    /**
     * BackupCleanMasterCommand constructor.
     * @param BackupProjectBehaviour $behaviour
     */
    public function __construct(BackupProjectBehaviour $behaviour)
    {
        $this->behaviour = $behaviour;
    }

    public function getName()
    {
        return 'Удалить все автоматические копии';
    }

    public function getCategory()
    {
        return 'backup';
    }

    public function getIcon()
    {
        return 'icons/trash16.gif';
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->behaviour->clearAutoBackupRequest();
    }

    public function makeUiForHead()
    {
        $button = $this->makeGlyphButton();
        $button->text = $this->getName();
        return $button;
    }
}