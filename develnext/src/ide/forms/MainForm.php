<?php
namespace ide\forms;

use ide\editors\form\IdeTabPane;
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
use php\gui\UXNode;
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
class MainForm extends AbstractIdeForm
{
    protected function init()
    {
        parent::init();
    }

    /**
     * @param IdeTabPane|UXNode $pane
     */
    public function setLeftPane($pane)
    {
        $this->clearLeftPane();

        if ($pane instanceof IdeTabPane) {
            $this->properties->children->add($pane->makeUi());
        } else {
            $this->properties->children->add($pane);
        }
    }

    public function clearLeftPane()
    {
        $this->properties->children->clear();
    }

    public function show()
    {
        parent::show();
        Logger::info("Show main form ...");

        $this->maximized = true;

        $this->projectTabs->tabs[0]->graphic = ico('settings16');
        //$this->projectTabs->tabs[1]->graphic = ico('tree16');
    }

    /**
     * @event close
     *
     * @param UXEvent $e
     *
     * @throws \Exception
     * @throws \php\io\IOException
     */
    public function doClose(UXEvent $e = null)
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
                    if ($e) {
                        $e->consume();
                    }
                    return;
                } else {
                    Logger::info("Cancel closing main form.");
                    Ide::get()->setUserConfigValue('lastProject', null);
                }

                Ide::get()->shutdown();
            } else {
                if ($e) {
                    $e->consume();
                }
            }
        } else {
            $dialog = new MessageBoxForm('Вы уверены, что хотите выйти из среды?', ['Да, выйти', 'Нет']);
            if ($dialog->showDialog() && $dialog->getResultIndex() == 0) {
                $this->hide();

                Ide::get()->shutdown();
            }
        }
    }

    /**
     * @return UXHBox
     */
    public function getHeadPane()
    {
        return $this->headPane;
    }

    /**
     * @deprecated
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