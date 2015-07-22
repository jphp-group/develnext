<?php
namespace ide\project\behaviours;

use ide\build\WindowsApplicationBuildType;
use ide\commands\BuildProjectCommand;
use ide\commands\CreateFormProjectCommand;
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
use php\gui\framework\Timer;
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

        $buildProjectCommand = new BuildProjectCommand();
        $buildProjectCommand->register(new WindowsApplicationBuildType());

        Ide::get()->registerCommand(new ExecuteProjectCommand());
        Ide::get()->registerCommand($buildProjectCommand);
        Ide::get()->registerCommand(new CreateFormProjectCommand());
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

        WatcherSystem::addPathRecursive($this->project->getFile(self::FORMS_DIRECTORY));

        $tree->addIgnoreRule('^src\\/\\.forms\\/.*\\.conf$');

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

    public function recoveryForm($filename)
    {
        $name = Str::sub($filename, 0, Str::length($filename) - 5);

        $form = $this->project->getAbsoluteFile($filename);
        $conf = $this->project->getAbsoluteFile($name . ".conf");

        $sources = $form->findLinkByExtension('php');

        $rel = FileUtils::relativePath($this->project->getFile("src/.forms/"), $filename);
        $rel = Str::sub($rel, 0, Str::length($rel) - 5);
        $rel = Str::replace($rel, '\\', '/');

        if (!$sources) {
            $sources = $this->project->getFile("src/app/forms/$rel.php");
            $form->addLink($sources);
        }

        if (!$sources->exists()) {
            $this->createForm($rel);
        }

        if (!$form->findLinkByExtension('conf')) {
            $form->addLink($conf);
        }
    }

    public function makeForm($name)
    {
        $form = $this->project->getFile("src/.forms/$name.fxml");
        $conf = $this->project->getFile("src/.forms/$name.conf");

        $sources = $this->project->getFile("src/app/forms/$name.php");

        if ($sources->exists() && !$form->findLinkByExtension('php')) {
            $form->addLink($sources);
        }

        if (!$form->findLinkByExtension('conf')) {
            $form->addLink($conf);
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

        $sources = $form->findLinkByExtension('php');

        if (!$sources) {
            $sources = $this->project->createFile("src/app/forms/$name.php", $template);
            $form->addLink($sources);
        } else {
            if (!$sources->exists()) {
                $sources->applyTemplate($template);
                $sources->updateTemplate(true);
            }
        }

        $conf = $form->findLinkByExtension('conf');

        if (!$conf) {
            $conf = $this->project->getFile("src/.forms/$name.conf");
            $form->addLink($conf);
        }

        $conf->setHiddenInTree(true);

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

        $formsPath = $this->project->getFile('src/app/forms');
        $formsPath->setHiddenInTree(true);

        $this->project->getFile('src/.forms')->scan(function ($name, File $file) {
            if ($file->isFile() && Str::endsWith($name, '.fxml')) {
                $this->recoveryForm($file);
            }
        });
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

    public function synchronizeDebugFiles()
    {
        $debugDir = Ide::get()->getOwnFile('debug/gui');

        if (!$debugDir->isDirectory() && Ide::get()->isDevelopment()) {
            $debugDir = Ide::get()->getOwnFile('misc/debug/gui');
        }

        if ($debugDir->isDirectory()) {
            FileUtils::scan($debugDir, function ($file) use ($debugDir) {
                $name = FileUtils::relativePath($debugDir, $file);

                FileUtils::copyFile($file, $this->project->getFile('src/.debug/' . $name));
            });
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