<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use ide\project\templates\DefaultGuiProjectTemplate;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\systems\WatcherSystem;
use php\gui\designer\UXDesigner;
use php\gui\event\UXEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXAlert;
use php\gui\UXButton;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXTextArea;
use php\gui\UXTreeView;

/**
 * @property UXTabPane $fileTabPane
 * @property UXTabPane $projectTabs
 * @property UXVBox $properties
 * @property UXTreeView $projectTree
 * @property UXHBox $headPane
 */
class MainForm extends AbstractForm
{
    protected function init()
    {
        parent::init();
    }

    public function show()
    {
        parent::show();
        Logger::info("Show main form ...");

        $this->maximized = true;

        $this->projectTabs->tabs[0]->graphic = ico('settings16');
        $this->projectTabs->tabs[1]->graphic = ico('tree16');
    }

    /**
     * @event close
     *
     * @param UXEvent $e
     *
     * @throws \Exception
     * @throws \php\io\IOException
     */
    public function doClose(UXEvent $e)
    {
        Logger::info("Close main form ...");

        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $dialog = new MessageBoxForm("Хотите открыть текущий проект ({$project->getName()}) при следующем запуске среды?", [
                'yes' => 'Да, открыть проект',
                'no'  => 'Нет',
                'abort' => 'Отмена, не закрывать среду'
            ]);
            $dialog->title = 'Закрытие проекта';

            if ($dialog->showDialog()) {
                $result = $dialog->getResult();

                if ($result == 'yes') {
                    Logger::info("Remember the last project = yes!");

                    Ide::get()->setUserConfigValue('lastProject', $project->getFile($project->getName() . '.dnproject'));
                } elseif ($result == 'abort') {
                    $e->consume();
                    return;
                } else {
                    Logger::info("Cancel closing main form.");
                    Ide::get()->setUserConfigValue('lastProject', null);
                }
            }
        }

        Ide::get()->shutdown();
    }

    /**
     * @return UXHBox
     */
    public function getHeadPane()
    {
        return $this->headPane;
    }

    /**
     * @return UXVBox
     */
    public function getPropertiesPane()
    {
        return $this->properties;
    }

    /**
     * @return UXTreeView
     */
    public function getProjectTree()
    {
        return $this->projectTree;
    }
}