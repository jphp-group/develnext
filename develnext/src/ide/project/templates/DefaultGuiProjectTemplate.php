<?php
namespace ide\project\templates;

use develnext\bundle\controlfx\ControlFXBundle;
use develnext\bundle\game2d\Game2DBundle;
use ide\bundle\std\UIDesktopBundle;
use ide\editors\FormEditor;
use ide\Logger;
use ide\project\AbstractProjectTemplate;
use ide\project\behaviours\BundleProjectBehaviour;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\behaviours\JavaPlatformBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\behaviours\RunBuildProjectBehaviour;
use ide\project\behaviours\ShareProjectBehaviour;
use ide\project\Project;
use ide\systems\FileSystem;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\lib\fs;

/**
 * Class DefaultGuiProjectTemplate
 * @package ide\project\templates
 */
class DefaultGuiProjectTemplate extends AbstractProjectTemplate
{
    public function getName()
    {
        return 'Десктопная программа';
    }

    public function getDescription()
    {
        return 'Программа с GUI интерфейсом для запуска на Linux/Windows/MacOS';
    }

    public function getIcon()
    {
        return 'icons/program16.png';
    }

    public function getIcon32()
    {
        return 'icons/programEx32.png';
    }

    public function recoveryProject(Project $project)
    {
        if (!$project->hasBehaviour(BundleProjectBehaviour::class)) {
            $project->register(new BundleProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(PhpProjectBehaviour::class)) {
            $project->register(new PhpProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(JavaPlatformBehaviour::class)) {
            $project->register(new JavaPlatformBehaviour(), false);
        }

        if (!$project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
            $project->register(new GuiFrameworkProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(RunBuildProjectBehaviour::class)) {
            $project->register(new RunBuildProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(ShareProjectBehaviour::class)) {
            $project->register(new ShareProjectBehaviour(), false);
        }

        $ideVersionHash = $project->getConfig()->getIdeVersionHash();
        if ($ideVersionHash < 2017022512) {
            $this->migrateFrom16RC2($project);
        }
    }

    private function migrateFrom16RC2(Project $project)
    {
        $openedFiles = [];
        $selectedFile = $project->getConfig()->getSelectedFile();

        foreach ($project->getConfig()->getOpenedFiles() as $file) {
            $openedFiles[$file] = $file;
        }

        // migrate forms...
        fs::scan($project->getFile('src/.forms'), function ($filename) use ($project, &$openedFiles, &$selectedFile) {
            $ext = fs::ext($filename);

            if ($ext == 'fxml' || $ext == 'conf') {
                $path = $project->getAbsoluteFile($filename)->getRelativePath();

                fs::copy($filename, $file = $project->getFile('src/app/forms/' . fs::name($filename)));
                fs::delete($filename);

                if ($ext == 'fxml') {
                    $file = fs::pathNoExt($file) . '.php';

                    if ($openedFiles[$path]) {
                        $openedFiles[$file] = $file;
                        unset($openedFiles[$path]);
                    }

                    if ($selectedFile == $path) {
                        $selectedFile = $file;
                    }
                }
            }
        });

        // migrate modules
        foreach ($project->getFile('src/.scripts')->findFiles() as $file) {
            if ($file->isDirectory()) {
                $path = $project->getAbsoluteFile($file)->getRelativePath();

                $phpFile = $project->getFile('src/app/modules/' . $file->getName() . '.php');
                $jsonFile = $project->getFile('src/app/modules/' . $file->getName() . '.json');

                $jsonData = (array) Json::fromFile($jsonFile);

                $moduleMeta = [
                    'props' => (array) $jsonData['properties'], 'components' => []
                ];

                foreach ($file->findFiles() as $scriptFile) {
                    $meta = Json::fromFile($scriptFile);

                    if ($meta['type']) {
                        $meta['props'] = (array)$meta['properties'];

                        unset($meta['id'], $meta['ideType'], $meta['properties']);

                        $moduleMeta['components'][fs::nameNoExt($scriptFile)] = $meta;
                    }
                }

                Json::toFile(fs::pathNoExt($phpFile) . '.module', $moduleMeta);

                if ($openedFiles[$path]) {
                    unset($openedFiles[$path]);
                    $openedFiles[$path] = $phpFile;
                }

                if ($selectedFile == $path) {
                    $selectedFile = $phpFile;
                }

                fs::delete($jsonFile);
                FileUtils::deleteDirectory($file);
            }
        }


        foreach ($project->getFile('src/app/modules')->findFiles() as $file) {
            if (fs::ext($file) == 'php') {
                $metaFile = fs::pathNoExt($file) . '.module';

                if (!fs::isFile($metaFile)) {
                    $jsonFile = fs::pathNoExt($file) . '.json';

                    $meta = ['props' => [], 'components' => []];

                    if (fs::isFile($jsonFile)) {
                        if ($oldMeta = Json::fromFile($jsonFile)) {
                            $meta['props'] = (array)$meta['properties'];
                        }

                        fs::delete($jsonFile);
                    }

                    Json::toFile($metaFile, $meta);
                }
            }
        }

        FileUtils::deleteDirectory($project->getFile('src/.scripts'));

        fs::delete($project->getFile('src/.system/modules.json'));
        FileUtils::deleteDirectory($project->getFile('src/.gradle'));

        $project->getConfig()->setTreeState(['/src/app/forms', '/src/app/modules']);
        $project->getConfig()->setOpenedFiles($openedFiles, $selectedFile);
        $project->getConfig()->save();
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function makeProject(Project $project)
    {
        /** @var BundleProjectBehaviour $bundle */
        $bundle = $project->register(new BundleProjectBehaviour());

        /** @var PhpProjectBehaviour $php */
        $php = $project->register(new PhpProjectBehaviour());
        $project->register(new JavaPlatformBehaviour());

        /** @var GuiFrameworkProjectBehaviour $gui */
        $gui = $project->register(new GuiFrameworkProjectBehaviour());

        $project->register(new RunBuildProjectBehaviour());
        $project->register(new ShareProjectBehaviour());

        $project->setIgnoreRules([
            '*.log', '*.tmp'
        ]);

        $project->on('create', function () use ($gui, $bundle, $php, $project) {
            $php->setImportType('package');

            $bundle->addBundle(Project::ENV_ALL, UIDesktopBundle::class, false);
            //$bundle->addBundle(Project::ENV_ALL, ControlFXBundle::class);
            $bundle->addBundle(Project::ENV_ALL, Game2DBundle::class);

            $appModule  = $gui->createModule('AppModule');
            $mainModule = $gui->createModule('MainModule');
            $mainForm   = $gui->createForm('MainForm');

            $project->getConfig()->setTreeState([
                "/src/{$project->getPackageName()}/forms",
                "/src/{$project->getPackageName()}/modules"
            ]);

            $gui->setMainForm('MainForm');

            FileSystem::open($mainModule);
            /** @var FormEditor $editor */
            $editor = FileSystem::open($mainForm);
            $editor->addModule('MainModule');
            $editor->saveConfig();
        });

        return $project;
    }

    public function isProjectWillMigrate(Project $project)
    {
        // check is < 16.5
        if ($project->getConfig()->getIdeVersionHash() < 2017022512) {
            return true;
        }

        return false;
    }
}