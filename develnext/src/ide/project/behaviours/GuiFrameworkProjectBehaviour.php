<?php
namespace ide\project\behaviours;

use ide\commands\ExecuteProjectCommand;
use ide\formats\templates\GuiApplicationConfFileTemplate;
use ide\formats\templates\GuiBootstrapFileTemplate;
use ide\formats\templates\GuiFormFileTemplate;
use ide\formats\templates\PhpClassFileTemplate;
use ide\Ide;
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
use php\util\Regex;

/**
 * Class GuiFrameworkProjectBehaviour
 * @package ide\project\behaviours
 */
class GuiFrameworkProjectBehaviour extends AbstractProjectBehaviour
{
    const FORMS_DIRECTORY = 'src/.forms';

    /** @var string */
    protected $mainForm = 'MainForm';

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('recover', [$this, 'doRecover']);
        $this->project->on('create', [$this, 'doCreate']);
        $this->project->on('open', [$this, 'doOpen']);
        $this->project->on('updateTree', [$this, 'doUpdateTree']);

        WatcherSystem::addListener([$this, 'doWatchFile']);

        Ide::get()->registerCommand(new ExecuteProjectCommand());
    }

    public function getMainForm()
    {
        return $this->mainForm;
    }

    public function doCreate()
    {
        $mainForm = $this->createForm($this->mainForm);
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

        FileSystem::open($this->makeForm($this->mainForm));

        WatcherSystem::addPathRecursive($this->project->getFile(self::FORMS_DIRECTORY));

        /** @var GradleProjectBehaviour $gradleBehavior */
        $gradleBehavior = $this->project->getBehaviour(GradleProjectBehaviour::class);

        $buildConfig = $gradleBehavior->getConfig();

        $buildConfig->addPlugin('application');
        $buildConfig->setDefine('mainClassName', '"php.runtime.launcher.Launcher"');
        $buildConfig->setSourceSet('main.resources.srcDirs', 'src');

        $buildConfig->addRepository('jcenter');
        $buildConfig->addRepository('mavenCentral');
        $buildConfig->addRepository('local', new File("lib/"));

        $buildConfig->setDependency('asm-all');
        $buildConfig->setDependency('jphp-runtime');
        $buildConfig->setDependency('jphp-core');
        $buildConfig->setDependency('jphp-gui-ext');
        $buildConfig->setDependency('jphp-gui-framework');
    }

    public function doUpdateTree(ProjectTree $tree, $path)
    {
        // ... todo.
    }

    public function doRecover()
    {
        if (!$this->project->hasBehaviour(GradleProjectBehavior::class)) {
            $this->project->register(new GradleProjectBehaviour());
        }

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

        $this->project->makeDirectory('src/app/forms');

        $this->project->getFile('src/app/forms')->setHiddenInTree(true);
    }

    public function synchronizeDependencies()
    {
        /** @var GradleProjectBehaviour $gradleBehavior */
        $gradleBehavior = $this->project->getBehaviour(GradleProjectBehaviour::class);

        $buildConfig = $gradleBehavior->getConfig();

        $libPath = $this->project->getFile('lib/');
        $libPath->mkdirs();

        foreach ($buildConfig->getDependencies() as $dep) {
            if (!$dep[0] && !$dep[2]) {
                $libFile = $this->findLibFile($dep[1]);

                if ($libFile) {
                    FileUtils::copyFile($libFile, "$libPath/$dep[1].jar");
                }
            }
        }
    }

    private function findLibFile($name)
    {
        /** @var File[] $libPaths */
        $libPaths = [Ide::get()->getOwnFile('lib/')];

        if (Ide::get()->isDevelopment()) {
            $ownFile = Ide::get()->getOwnFile('build/install/develnext/lib');
            $libPaths[] = $ownFile;
        }

        $regex = Regex::of('(\.[0-9]|\-[0-9])');

        $name = $regex->with($name)->replace('');

        foreach ($libPaths as $libPath) {
            foreach ($libPath->findFiles() as $file) {
                $filename = $regex->with($file->getName())->replace('');

                if (Str::endsWith($filename, '.jar') || Str::endsWith($filename, '-SNAPSHOT.jar')) {
                    $filename = Str::sub($filename, 0, Str::length($filename) - 4);

                    if (Str::endsWith($filename, '-SNAPSHOT')) {
                        $filename = Str::sub($filename, 0, Str::length($filename) - 9);
                    }

                    if ($filename == $name) {
                        return $file;
                    }
                }
            }
        }

        return null;
    }
}