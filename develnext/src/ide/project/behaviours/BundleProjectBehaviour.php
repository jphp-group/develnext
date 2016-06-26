<?php
namespace ide\project\behaviours;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\editors\ProjectEditor;
use ide\forms\BundleCheckListForm;
use ide\Ide;
use ide\IdeConfiguration;
use ide\library\IdeLibraryBundleResource;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\project\Project;
use ide\systems\IdeSystem;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\lib\arr;
use php\lib\fs;
use php\lib\reflect;
use php\lib\str;
use php\util\Configuration;

/**
 * Class BundleProjectBehaviour
 * @package ide\project\behaviours
 */
class BundleProjectBehaviour extends AbstractProjectBehaviour
{
    const CONFIG_BUNDLE_KEY_USE_IMPORTS = 'useImports';

    const GENERATED_DIRECTORY = 'src_generated';
    const VENDOR_DIRECTORY = 'vendor';

    /**
     * @var UXNode
     */
    protected $uiSettings;

    /**
     * @var UXHBox
     */
    protected $uiPackages;

    /**
     * @var AbstractBundle[]
     */
    protected $bundles = [];

    /**
     * @var array
     */
    protected $bundlesCannotBeRemoved = [];

    /**
     * @var Configuration[]
     */
    protected $bundleConfigs = [];

    /**
     * @var UXCheckbox
     */
    protected $uiUseImportCheckbox;

    /**
     * @var array
     */
    protected $fileStat = [];

    /**
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_SYSTEM;
    }

    /**
     * ...
     */
    public function inject()
    {
        // Do not save bundles conf via project.
        $this->project->addIdeConfigConfigurer(__CLASS__, function (IdeConfiguration $conf) {
            if (str::startsWith($conf->getShortName(), "bundles/")) {
                $conf->setAutoSave(false);
            }
        });

        foreach ($this->getPublicBundles(true) as $bundle) {
            IdeSystem::getLoader()->addClassPath($bundle->getVendorDirectory());
        }

        $this->project->setSrcDirectory('src');
        $this->project->setSrcGeneratedDirectory(self::GENERATED_DIRECTORY);

        $this->project->on('save', [$this, 'doSave']);
        $this->project->on('open', [$this, 'doLoad']);
        $this->project->on('close', [$this, 'doClose']);
        $this->project->on('preCompile', [$this, 'doPreCompile']);
        $this->project->on('makeSettings', [$this, 'doMakeSettings']);
        $this->project->on('updateSettings', [$this, 'doUpdateSettings']);
        $this->project->on('create', [$this, 'doCreate']);
    }

    public function doCreate()
    {
        uiLater(function () {
            $this->showBundleCheckListDialog();
        });
    }

    public function doClose()
    {
        foreach ($this->bundles as $env => $bundles) {
            /** @var AbstractBundle $bundle */
            foreach ($bundles as $bundle) {
                $bundle->onRemove($this->project);

                foreach ($this->getDependenciesOfBundle($env, $bundle) as $one) {
                    $one->onRemove($this->project, $bundle);
                }
            }
        }
    }

    public function doSave()
    {
        fs::clean($this->project->getIdeFile('bundles/'), function ($filename) {
            if (fs::ext($filename) == 'conf') {
                return true;
            }

            return false;
        });

        foreach ($this->bundles as $env => $group) {
            /** @var AbstractBundle $bundle */
            foreach ($group as $bundle) {
                $type = get_class($bundle);
                $type = str::replace($type, '\\', '.');

                $config = $this->project->getIdeConfig("bundles/$type.conf");
                $config->set('env', $env);

                $bundle->onSave($this->project, $config);
                $config->save();
            }
        }

        if ($this->uiSettings) {
            $this->setIdeConfigValue(self::CONFIG_BUNDLE_KEY_USE_IMPORTS, $this->uiUseImportCheckbox->selected);
        }
    }

    public function doLoad()
    {
        $files = $this->project->getIdeFile("bundles/")->findFiles();

        foreach ($files as $file) {
            if (fs::ext($file) == 'conf' && fs::isFile($file)) {
                $config = $this->project->getIdeConfig("bundles/" . fs::name($file));

                $class = str::replace(fs::nameNoExt($file), '.', '\\');

                if (class_exists($class)) {
                    $bundle = $this->makeBundle($class);

                    if ($bundle instanceof AbstractBundle) {
                        $this->bundleConfigs[get_class($bundle)] = $config;

                        $bundle->onLoad($this->project, $config);
                        $this->addBundle($config->get('env') ?: Project::ENV_ALL, $class);
                    }
                }
            }
        }
    }

    /**
     * @param $class
     * @return AbstractBundle
     */
    public function makeBundle($class)
    {
        /** @var AbstractBundle $bundle */
        $bundle = null;

        /** @var IdeLibraryBundleResource $resource */
        foreach (Ide::get()->getLibrary()->getResources('bundles') as $resource) {
            if (reflect::typeOf($resource->getBundle()) == $class) {
                $bundle = $resource->getBundle();
                break;
            }
        }

        if (!$bundle) {
            $bundle = new $class();
        }

        return $bundle;
    }

    protected function tryFileChange($filename, callable $handle)
    {
        $filename = fs::normalize($filename);

        $stat = $this->fileStat[FileUtils::hashName($filename)];

        $fTime = fs::time("$filename");
        if ($fTime <= (int)$stat['time']) {
            //return false;
        }

        $fHash = fs::hash("$filename");
        if ($fHash === $stat['hash']) {
            return false;
        }

        $handle();

        $this->fileStat[FileUtils::hashName($filename)] = [
            'time' => fs::time($filename), 'hash' => fs::hash($filename)
        ];

        return true;
    }

    protected function doPreCompileUseImports($env, callable $log = null)
    {
        $gradle = GradleProjectBehaviour::get();

        if ($gradle) {
            $config = $gradle->getConfig();

            foreach ($this->getPublicBundles(true) as $bundle) {
                $config->removeSourceSet('main.resources.srcDirs', self::VENDOR_DIRECTORY . "/{$bundle->getVendorName()}");
            }

            foreach ($this->fetchAllBundles($env) as $bundle) {
                if ($gradle) {
                    $config = $gradle->getConfig();

                    $config->addSourceSet('main.resources.srcDirs', self::VENDOR_DIRECTORY . "/{$bundle->getVendorName()}");
                }
            }

            $config->save();
        }

        if ($this->getIdeConfigValue(self::CONFIG_BUNDLE_KEY_USE_IMPORTS, false)) {
            $withSourceMap = Project::ENV_DEV == $env;
            static $prevImports = [];

            $imports = [];

            $allBundles = $this->fetchAllBundles($env);

            foreach ($allBundles as $bundle) {
                foreach ($bundle->getUseImports() as $useImport) {
                    $imports[$useImport] = [$useImport];
                }
            }

            if ($prevImports != $imports) {
                $this->fileStat = [];
            }

            $prevImports = $imports;

            if ($imports) {
                FileUtils::scan($this->project->getFile('src/app'), function ($filename) use ($imports, $log, $withSourceMap) {
                    if (str::endsWith($filename, '.php') && fs::isFile("$filename.source")) {
                        $filename = fs::normalize($filename);
                        $file = $this->project->getAbsoluteFile($filename);

                        $stat = $this->fileStat[FileUtils::hashName($filename)];

                        $fTime = fs::time("$filename");
                        if ($fTime <= (int)$stat['time']) {
                            //return;
                        }

                        $fHash = fs::hash("$filename");
                        if ($fHash === $stat['hash']) {
                            //return;
                        }

                        $phpParser = PhpParser::ofFile($filename, $withSourceMap);
                        $phpParser->addUseImports($imports);

                        if ($log) {
                            if (!$file->exists()) {
                                return;
                            }

                            $log(":import use '{$file->getRelativePath()}'");
                        }

                        $phpParser->saveContent($filename, $withSourceMap);

                        $this->fileStat[FileUtils::hashName($filename)] = [
                            'time' => $fTime, 'hash' => $fHash
                        ];
                    }
                });
            }
        }
    }

    public function doPreCompile($env, callable $log = null)
    {
        $generatedDirectory = $this->project->getSrcFile('', true);
        fs::clean($generatedDirectory);
        fs::makeDir($generatedDirectory);

        //FileUtils::deleteDirectory($this->project->getFile(self::VENDOR_DIRECTORY));

        FileUtils::scan($this->project->getSrcFile(''), function ($filename) {
            if (str::endsWith($filename, '.php.sourcemap')) {
                fs::delete($filename);
            }

            if (str::endsWith($filename, '.php.source')) {
                //$this->tryFileChange("$filename.php.source", function () use ($filename) {
                FileUtils::copyFile($filename, fs::pathNoExt($filename)); // rewrite from origin.
                //});
            }
        });

        $gradle = GradleProjectBehaviour::get();
        $allBundles = $this->fetchAllBundles($env);

        if ($gradle) {
            $gradle->addJcenterRepository();
            $gradle->addMavenCentralRepository();
            $gradle->addMavenLocalRepository();
            $gradle->addLocalLibRepository();

            foreach ($allBundles as $bundle) {
                if ($log) {
                    $log(':apply-bundle "' . $bundle->getName() . '"');
                }

                $bundle->applyForGradle($gradle);
            }

            foreach ($allBundles as $bundle) {
                $bundle->onPreCompile($this->project, $env, $log);
            }
        }

        $this->doPreCompileUseImports($env, $log);
    }

    /**
     * @param $env
     * @param AbstractBundle $bundle
     * @return AbstractBundle[]
     */
    public function getDependenciesOfBundle($env, AbstractBundle $bundle)
    {
        $result = [];

        $fetchDependencies = function ($dependencies) use (&$result, &$fetchDependencies, $env) {
            foreach ($dependencies as $dep) {
                if (!$result[$dep]) {
                    $result[$dep] = $one = $this->fetchBundle($env, $dep);

                    $fetchDependencies($one->getDependencies());
                }
            }
        };

        $fetchDependencies($bundle->getDependencies());

        return $result;
    }

    /**
     * @param $env
     * @return \ide\bundle\AbstractBundle[]
     */
    public function fetchAllBundles($env)
    {
        $result = [];

        $fetchDependencies = function ($dependencies) use ($env, &$result, &$fetchDependencies) {
            foreach ($dependencies as $dep) {
                if (!$result[$dep]) {
                    $result[$dep] = $one = $this->fetchBundle($env, $dep);

                    $fetchDependencies($one->getDependencies());
                }
            }
        };

        $groups = [(array)$this->bundles[$env]];

        if ($env != Project::ENV_ALL) {
            $groups[] = (array)$this->bundles[Project::ENV_ALL];
        }

        /** @var AbstractBundle $bundle */
        foreach ($groups as $group) {
            foreach ($group as $bundle) {
                $fetchDependencies($bundle->getDependencies());

                $type = get_class($bundle);

                if (!$result[$type]) {
                    $result[$type] = $bundle;
                }
            }
        }

        return $result;
    }

    /**
     * @param $env
     * @param $class
     * @return AbstractBundle
     */
    public function fetchBundle($env, $class)
    {
        if ($bundle = $this->bundles[$env][$class]) {
            return $bundle;
        }

        if ($bundle = $this->bundles[Project::ENV_ALL][$class]) {
            return $bundle;
        }

        return $bundle = $this->makeBundle($class);
    }

    /**
     * @param $env
     * @param $class
     * @return bool
     */
    public function hasBundle($env, $class)
    {
        if ($bundle = $this->bundles[$env][$class]) {
            return true;
        }

        return false;
    }

    /**
     * @return \ide\bundle\AbstractBundle[]
     */
    public function getBundles()
    {
        return $this->bundles;
    }

    /**
     * @param bool $hidden
     * @return \ide\bundle\AbstractBundle[]
     */
    public function getPublicBundles($hidden = false)
    {
        $result = [];

        /** @var IdeLibraryBundleResource $resource */
        foreach (Ide::get()->getLibrary()->getResources('bundles') as $resource) {
            if (!$resource->isHidden() || $hidden) {
                $result[reflect::typeOf($resource->getBundle())] = $resource->getBundle();
            }
        }

        return $result;
    }

    /**
     * @param string[] $classes
     */
    public function setBundles(array $classes)
    {
        $classes = arr::combine($classes, $classes);

        $removed = [];

        foreach ($this->bundles as $bundles) {
            foreach ($bundles as $class => $bundle) {
                if (!$classes[$class]) {
                    $removed[] = $class;
                }
            }
        }

        foreach ($removed as $class) {
            $this->removeBundle($class);
        }

        foreach ($classes as $class) {
            $this->addBundle(Project::ENV_ALL, $class);
        }
    }

    /**
     * @param string $env
     * @param string $class
     * @param bool $canRemove
     */
    public function addBundle($env, $class, $canRemove = true)
    {
        if (!$this->bundles[$env][$class]) {
            unset($this->bundles[Project::ENV_ALL][$class]);

            $bundle = $this->makeBundle($class);
            $this->bundles[$env][$class] = $bundle;

            foreach ($this->getDependenciesOfBundle($env, $bundle) as $one) {
                $one->onAdd($this->project, $bundle);
            }

            $bundle->onAdd($this->project);
        }

        if (!$canRemove) {
            $this->bundlesCannotBeRemoved[$class] = $class;
        }

        $this->project->clearIdeCache('bytecode');
    }

    /**
     * @param $class
     * @param bool $force true to remove that cannot be removed.
     */
    public function removeBundle($class, $force = false)
    {
        $removed = false;

        if (!$force && $this->bundlesCannotBeRemoved[$class]) {
            return;
        }

        foreach ($this->bundles as $env => $bundles) {
            /** @var AbstractBundle $bundle */
            if ($bundle = $bundles[$class]) {
                if (!$removed) {
                    $bundle->onRemove($this->project);

                    foreach ($this->getDependenciesOfBundle($env, $bundle) as $one) {
                        $one->onRemove($this->project, $bundle);
                    }
                }

                $this->bundles[$env][$class] = null;

                $removed = true;
            }
        }

        $newBundles = [];

        foreach ($this->bundles as $env => $bundles) {
            $newBundles[$env] = [];

            foreach ($bundles as $class => $bundle) {
                if ($bundle != null) {
                    $newBundles[$env][$class] = $bundle;
                }
            }
        }

        $this->bundles = $newBundles;

        $this->project->clearIdeCache('bytecode');
    }

    /**
     * @param $name
     * @return null
     */
    public function findClassByShortName($name)
    {
        foreach ($this->fetchAllBundles(Project::ENV_ALL) as $one) {
            if ($one instanceof AbstractJarBundle) {
                foreach ($one->getUseImports() as $import) {
                    $_name = fs::name($import);

                    if (str::equalsIgnoreCase($name, $_name)) {
                        return $import;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param AbstractBundle $bundle
     * @return Configuration
     */
    public function getBundleConfig(AbstractBundle $bundle)
    {
        return $this->bundleConfigs[get_class($bundle)];
    }

    public function showBundleCheckListDialog()
    {
        $dialog = new BundleCheckListForm($this);

        if ($dialog->showDialog() && $dialog->getResult() !== null) {
            $classes = arr::keys($dialog->getResult());
            $this->setBundles($classes);

            $this->doUpdateSettings();
            $this->doSave();
        }
    }

    public function doUpdateSettings(CommonProjectControlPane $editor = null)
    {
        if ($this->uiSettings) {
            $this->uiPackages->children->clear();

            $bundles = $this->bundles[Project::ENV_ALL];

            /** @var AbstractBundle $bundle */
            foreach ($bundles as $bundle) {
                $uiItem = new UXButton($bundle->getName());
                $uiItem->graphic = ico('bundle16');
                $uiItem->classes->add('dn-simple-button');
                $uiItem->tooltipText = $bundle->getDescription();

                $this->uiPackages->add($uiItem);
            }

            $addButton = new UXButton();
            $addButton->graphic = ico('edit16');
            $addButton->on('action', function () {
                $this->showBundleCheckListDialog();
            });
            $this->uiPackages->add($addButton);

            $this->uiUseImportCheckbox->selected = $this->getIdeConfigValue(self::CONFIG_BUNDLE_KEY_USE_IMPORTS, false);
        }
    }

    public function doMakeSettings(CommonProjectControlPane $editor)
    {
        $title = new UXLabel('Пакеты:');
        $title->font = $title->font->withBold();

        $packages = new UXFlowPane();
        $packages->hgap = $packages->vgap = 5;
        $this->uiPackages = $packages;

        $this->uiUseImportCheckbox = $useImportCheckbox = new UXCheckbox("Добавлять use импорты классов (устарело)");
        $this->uiUseImportCheckbox->on('mouseUp', [$this, 'doSave']);
        $useImportCheckbox->tooltipText = 'Добавлять во все исходники подключение классов через use из всех пакетов';

        $ui = new UXVBox([$title, $packages, $useImportCheckbox]);
        $ui->spacing = 5;

        $this->uiSettings = $ui;

        $editor->addSettingsPane($ui);
    }
}