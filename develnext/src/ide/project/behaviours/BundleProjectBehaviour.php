<?php
namespace ide\project\behaviours;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\editors\AbstractEditor;
use ide\editors\ProjectEditor;
use ide\formats\ProjectFormat;
use ide\forms\BundleDetailInfoForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\IdeConfiguration;
use ide\library\IdeLibraryBundleResource;
use ide\Logger;
use ide\project\AbstractProjectBehaviour;
use ide\project\behaviours\bundle\BundlesProjectControlPane;
use ide\project\control\CommonProjectControlPane;
use ide\project\Project;
use ide\project\ProjectModule;
use ide\systems\FileSystem;
use ide\systems\IdeSystem;
use ide\utils\FileUtils;
use ide\utils\PhpParser;
use php\gui\designer\UXDirectoryTreeValue;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXCheckbox;
use php\gui\UXDialog;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\io\File;
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

        $this->project->on('createEditor', [$this, 'doCreateEditor']);
    }

    public function doCreate()
    {
        /*uiLater(function () {
            $this->showBundleCheckListDialog();
        });*/
    }

    public function doCreateEditor(AbstractEditor $editor)
    {
        $file = $editor->getFile();

        $path = $this->project->getAbsoluteFile($file)->getRelativePath();

        if (str::startsWith($path, self::VENDOR_DIRECTORY . '/')) {
            $editor->setReadOnly(true);
        }
    }

    public function doClose()
    {
        foreach ($this->bundles as $env => $bundles) {
            /** @var AbstractBundle $bundle */
            foreach ($bundles as $bundle) {
                try {
                    $bundle->onRemove($this->project);
                } catch (\Throwable $e) {
                    Logger::exception("Error in onRemove event handler, {$e->getMessage()}", $e);
                }

                foreach ($this->getDependenciesOfBundle($env, $bundle) as $one) {
                    try {
                        $one->onRemove($this->project, $bundle);
                    } catch (\Throwable $e) {
                        Logger::exception("Error in onRemove (in dependency) event handler, {$e->getMessage()}", $e);
                    }
                }
            }
        }

        //$this->bundleConfigs = [];
        // $this->bundles = [];
        $this->fileStat = [];
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

                $bundleResource = $this->getResourceOfBundle($bundle);

                $config = $this->project->getIdeConfig("bundles/$type.conf");
                $config->set('env', $env);

                if ($bundleResource) {
                    $config->set('version', $bundleResource->getVersion());
                    $config->set('name', $bundleResource->getName());
                }

                $bundle->onSave($this->project, $config);
                $config->saveFile();
            }
        }

        if ($this->uiSettings) {
            $this->setIdeConfigValue(self::CONFIG_BUNDLE_KEY_USE_IMPORTS, $this->uiUseImportCheckbox->selected);
        }
    }

    public function doLoad()
    {
        /** @var ProjectFormat $projectFormat */
        $projectFormat = $this->project->getRegisteredFormat(ProjectFormat::class);
        $projectFormat->addControlPane(new BundlesProjectControlPane($this));

        $this->project->getTree()->addValueCreator(function ($path, File $file) {
            if ($file->isDirectory() && str::startsWith($path, '/' . self::VENDOR_DIRECTORY . '/')) {
                $names = str::split($path, '/', 100);

                if (sizeof($names) === 3) {
                    $class = $names[2];
                    $class = str::replace($class, '.', '\\');

                    $resource = $this->getResourceOfBundle($class);

                    if ($resource) {
                        $icon = Ide::get()->getImage($resource->getIcon(), [16, 16]);

                        if (!$icon) {
                            $icon = ico('blocks16');
                        }

                        return new UXDirectoryTreeValue($path, $file->getName(), $resource->getName(), $icon, $icon, true);
                    }
                }
            }
        });

        $files = $this->project->getIdeFile("bundles/")->findFiles();

        $unknownBundles = [];

        foreach ($files as $file) {
            if (fs::ext($file) == 'conf' && fs::isFile($file)) {
                $config = $this->project->getIdeConfig("bundles/" . fs::name($file));

                $class = str::replace(fs::nameNoExt($file), '.', '\\');

                if (class_exists($class)) {
                    $bundle = $this->makeBundle($class);

                    if ($bundle->useNewBundles()) {
                        foreach ($bundle->useNewBundles() as $newClass) {
                            $this->addBundle($config->get('env') ?: Project::ENV_ALL, $newClass);
                        }

                        $this->removeBundle($class, true);
                    } else {
                        if ($bundle instanceof AbstractBundle) {
                            $this->bundleConfigs[get_class($bundle)] = $config;

                            $bundle->onLoad($this->project, $config);
                            $this->addBundle($config->get('env') ?: Project::ENV_ALL, $class);
                        }
                    }
                } else {
                    $unknownBundles[$class] = $config;
                }
            }
        }

        if ($unknownBundles) {
            $text = [];

            foreach ($unknownBundles as $class => $config) {
                $text[] = $config->get('name', $class) . " " . $config->get('version', '1.0');
            }

            $text = " - " . str::join($text, "\n - ");

            $warning = new MessageBoxForm("Данный проект не будет работать корректно, т.к. содержит неизвестные пакеты расширений: \n\n$text", ['ОК, Игнорировать']);
            $warning->showWarningDialog();
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
                fs::scan($this->project->getSrcFile('app'), function ($filename) use ($imports, $log, $withSourceMap) {
                    if (str::endsWith($filename, '.php')) {
                        $genFilename = $this->project->getSrcFile(FileUtils::relativePath($this->project->getSrcFile(''), $filename), true);

                        if (!fs::exists($genFilename)) {
                            FileUtils::copyFile($filename, $genFilename);
                        }

                        $filename = $genFilename;

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
        $result = fs::clean($generatedDirectory);

        if ($result['error']) {
            throw new \Exception("Unable to clean src_generated directory, files = [" . str::join($result['error'], ', ') . "]");
        }

        fs::makeDir($generatedDirectory);

        fs::scan($this->project->getSrcFile(''), function ($filename) {
            if (str::endsWith($filename, '.php.sourcemap')) {
                fs::delete($filename);
            }
        });

        return function ($env, callable $log = null) {
            $allBundles = $this->fetchAllBundles($env);

            foreach ($allBundles as $bundle) {
                if ($log) {
                    $log(':apply-bundle "' . reflect::typeOf($bundle) . '"');
                }

                $bundle->onPreCompile($this->project, $env, $log);
            }

            $this->doPreCompileUseImports($env, $log);
        };
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
     * @param string $class
     * @return bool
     */
    public function hasBundle($env, $class)
    {
        if ($class instanceof AbstractBundle) $class = reflect::typeOf($class);

        if ($bundle = $this->bundles[$env][$class]) {
            return true;
        }

        return false;
    }

    /**
     * @param string $class
     * @return string|null
     */
    public function hasBundleInAnyEnvironment($class)
    {
        foreach ($this->bundles as $env => $group) {
            if ($this->hasBundle($env, $class)) return $env;
        }

        return null;
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
     * @param bool $hidden
     * @return IdeLibraryBundleResource[]
     */
    public function getPublicBundleResources($hidden = false)
    {
        $result = [];

        /** @var IdeLibraryBundleResource $resource */
        foreach (Ide::get()->getLibrary()->getResources('bundles') as $resource) {
            if (!$resource->isHidden() || $hidden) {
                $result[] = $resource;
            }
        }

        return $result;
    }

    /**
     * @param AbstractBundle|string $bundleOrClass
     * @return IdeLibraryBundleResource|null
     */
    public function getResourceOfBundle($bundleOrClass)
    {
        $class = $bundleOrClass instanceof AbstractBundle ? reflect::typeOf($bundleOrClass) : (string)$bundleOrClass;

        /** @var IdeLibraryBundleResource $resource */
        foreach (Ide::get()->getLibrary()->getResources('bundles') as $resource) {
            if (reflect::typeOf($resource->getBundle()) == $class) {
                return $resource;
            }
        }

        return null;
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
     * @param string|AbstractBundle $class
     * @param bool $canRemove
     */
    public function addBundle($env, $class, $canRemove = true)
    {
        if ($class instanceof AbstractBundle) $class = reflect::typeOf($class);

        if (!$this->bundles[$env][$class]) {
            unset($this->bundles[Project::ENV_ALL][$class]);

            $bundle = $this->makeBundle($class);

            $this->bundles[$env][$class] = $bundle;

            $dependenciesOfBundle = $this->getDependenciesOfBundle($env, $bundle);

            foreach ($dependenciesOfBundle as $one) {
                // include public dep
                if ($resource = $this->getResourceOfBundle($one)) {
                    if (!$resource->isHidden()) {
                        $this->addBundle($env, $resource->getBundle());
                        continue;
                    }
                }

                // include hidden dep
                try {
                    $one->onAdd($this->project, $bundle);
                } catch (\Throwable $e) {
                    Logger::exception("Error in onAdd (in dependency) event handler, {$e->getMessage()}", $e);
                }
            }

            try {
                $bundle->onAdd($this->project);
            } catch (\Throwable $e) {
                Logger::exception("Error in onAdd event handler, {$e->getMessage()}", $e);
            }

            $this->project->clearIdeCache('bytecode');
        }

        if (!$canRemove) {
            $this->bundlesCannotBeRemoved[$class] = $class;
        }
    }

    /**
     * @param AbstractBundle|string $class
     * @param bool $force true to remove that cannot be removed.
     */
    public function removeBundle($class, $force = false)
    {
        if ($class instanceof AbstractBundle) $class = reflect::typeOf($class);

        $removed = false;

        if (!$force && $this->bundlesCannotBeRemoved[$class]) {
            return;
        }

        foreach ($this->bundles as $env => $bundles) {
            /** @var AbstractBundle $bundle */
            if ($bundle = $bundles[$class]) {
                if (!$removed) {
                    try {
                        $bundle->onRemove($this->project);
                    } catch (\Throwable $e) {
                        Logger::exception("Error in onRemove event handler, {$e->getMessage()}", $e);
                    }

                    $dependenciesOfBundle = $this->getDependenciesOfBundle($env, $bundle);

                    foreach ($dependenciesOfBundle as $one) {
                        // do not remove public dep.
                        if ($resource = $this->getResourceOfBundle($one)) {
                            if (!$resource->isHidden()) {
                                continue;
                            }
                        }

                        try {
                            $one->onRemove($this->project, $bundle);
                        } catch (\Throwable $e) {
                            Logger::exception("Error in onRemove (in dependency) event handler, {$e->getMessage()}", $e);
                        }
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
     * @return array
     */
    public function getSourceDirectories()
    {
        if ($gradle = GradleProjectBehaviour::get()) {
            return arr::toList($gradle->getConfig()->getSourceSets('main.resources.srcDirs'));
        } else {
            return ['src_generated/', 'src'];
        }
    }

    /**
     * @param AbstractBundle $bundle
     * @return Configuration
     */
    public function getBundleConfig(AbstractBundle $bundle)
    {
        return $this->bundleConfigs[get_class($bundle)];
    }

    public function showBundleDetailDialog(IdeLibraryBundleResource $resource = null)
    {
        if (!$resource) {
            return;
        }

        if ($resource && $resource->isHidden()) {
            UXDialog::showAndWait("{$resource->getName()} - Системный пакет расширений.");
            return;
        }

        $dialog = new BundleDetailInfoForm($this);
        $dialog->setResult($resource);

        if ($dialog->showDialog() || true) {
            /*$classes = arr::keys($dialog->getResult());
            $this->setBundles($classes);*/

            $this->doUpdateSettings();
            $this->doSave();

            if ($editor = FileSystem::getSelectedEditor()) {
                $editor->open();
                $editor->refresh();
            }
        }
    }

    public function doUpdateSettings(CommonProjectControlPane $editor = null)
    {
        if ($this->uiSettings) {
            $this->uiPackages->children->clear();

            $bundles = $this->bundles[Project::ENV_ALL];

            /** @var AbstractBundle $bundle */
            foreach ($bundles as $bundle) {
                $resource = $this->getResourceOfBundle($bundle);

                $uiItem = new UXButton($resource ? $resource->getName() : (new \ReflectionClass($bundle))->getShortName());
                $uiItem->graphic = ($resource && $resource->getIcon()) ? Ide::get()->getImage($resource->getIcon(), [16, 16]) : ico('bundle16');
                $uiItem->padding = [7, 12];
                $uiItem->classes->add('dn-simple-toggle-button');
                $uiItem->tooltipText = $bundle->getDescription();
                $uiItem->on('action', function () use ($resource) {
                    $this->showBundleDetailDialog($resource);
                });

                $this->uiPackages->add($uiItem);
            }

            $addButton = new UXButton();
            $addButton->graphic = ico('edit16');
            $addButton->classes->add('flat-button');
            $addButton->text = 'Изменить';
            $addButton->on('action', function () {
                /** @var ProjectEditor $projectEditor */
                $projectEditor = FileSystem::open($this->project->getProjectFile());

                if ($projectEditor instanceof ProjectEditor) {
                    $projectEditor->navigate(BundlesProjectControlPane::class);
                } else {
                    UXDialog::show('Unable to navigate the project editor', 'ERROR');
                }
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