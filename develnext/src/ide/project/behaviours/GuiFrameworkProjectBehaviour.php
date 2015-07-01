<?php
namespace ide\project\behaviours;

use ide\formats\templates\GuiApplicationConfFileTemplate;
use ide\formats\templates\GuiBootstrapFileTemplate;
use ide\formats\templates\GuiFormFileTemplate;
use ide\formats\templates\PhpClassFileTemplate;
use ide\project\AbstractProjectBehaviour;
use ide\project\ProjectFile;
use ide\project\ProjectTree;
use ide\project\ProjectTreeItem;
use ide\systems\FileSystem;
use ide\systems\WatcherSystem;
use ide\utils\FileUtils;
use php\gui\UXTreeItem;
use php\gui\UXTreeView;
use php\io\File;
use php\lib\Str;

/**
 * Class GuiFrameworkProjectBehaviour
 * @package ide\project\behaviours
 */
class GuiFrameworkProjectBehaviour extends AbstractProjectBehaviour
{
    const FORMS_DIRECTORY = 'src/.forms';

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('recoverFiles', [$this, 'doRecoverFiles']);
        $this->project->on('create', [$this, 'doCreate']);
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('updateTree', [$this, 'doUpdateTree']);

        WatcherSystem::addListener([$this, 'doWatchFile']);
    }

    public function getMainForm()
    {
        return 'MainForm';
    }

    public function doCreate()
    {
        $mainForm = $this->createForm('MainForm');

        FileSystem::open($mainForm);
    }

    public function doWatchFile(ProjectFile $file, $event)
    {
        if ($file) {
            $path = $file->getRelativePath();

            switch ($event['kind']) {
                case 'ENTRY_CREATE':
                case 'ENTRY_DELETE':
                    if (Str::startsWith($path, self::FORMS_DIRECTORY)) {
                        $this->updateFormsInTree();
                    }

                    break;
            }
        }
    }

    public function doOpen()
    {
        $tree = $this->project->getTree();

        $formsItem = $tree->getOrCreateItem('forms', 'Формы', 'icons/forms16.png');
        $formsItem->setExpanded(true);

        $formsItem->onUpdate(function () {
            $this->updateFormsInTree();
        });

        WatcherSystem::addPathRecursive($this->project->getFile(self::FORMS_DIRECTORY));
    }

    public function doUpdateTree(ProjectTree $tree, $path)
    {
        // ... todo.
    }

    public function doRecoverFiles()
    {
        $this->_recoverDirectories();

        $this->project->defineFile('src/JPHP-INF/.bootstrap.php', new GuiBootstrapFileTemplate());
        $this->project->defineFile('src/.system/application.conf', new GuiApplicationConfFileTemplate($this->project));
    }

    public function updateFormsInTree()
    {
        $tree = $this->project->getTree();

        $tree->updateDirectory('forms', $this->project->getFile(self::FORMS_DIRECTORY));
    }

    public function makeForm($name)
    {
        $form = $this->project->getFile("src/.forms/$name.fxml");
        $sources = $this->project->getFile("src/app/forms/$name.php");

        if ($sources->exists() && !$form->findLinkByExtension('php')) {
            $form->addLink($sources);
        }

        return $form;
    }

    public function createForm($name)
    {
        $form = $this->project->createFile("src/.forms/$name.fxml", new GuiFormFileTemplate());

        $template = new PhpClassFileTemplate($name, 'AbstractForm');
        $template->setNamespace('app\\forms');
        $template->setImports([
            'php\\gui\\framework\\AbstractForm'
        ]);

        $sources = $this->project->createFile("src/app/forms/$name.php", $template);

        $form->addLink($sources);

        return $form;
    }

    protected function _recoverDirectories()
    {
        $this->project->makeDirectory('src/');
        $this->project->makeDirectory('src/.data');
        $this->project->makeDirectory('src/.data/img');
        $this->project->makeDirectory('src/.forms');
        $this->project->makeDirectory('src/.system');
        $this->project->makeDirectory('src/JPHP-INF');

        $this->project->makeDirectory('src/app');
        $this->project->makeDirectory('src/app/forms/');
    }
}