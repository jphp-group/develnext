<?php
namespace ide\project\behaviours;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use ide\editors\ProjectEditor;
use ide\project\AbstractProjectBehaviour;
use ide\project\Project;
use php\gui\layout\UXHBox;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\lib\fs;
use php\lib\str;
use php\util\Configuration;

/**
 * Class BundleProjectBehaviour
 * @package ide\project\behaviours
 */
class BundleProjectBehaviour extends AbstractProjectBehaviour
{
    const CONFIG_BUNDLE_KEY_USE_IMPORTS = 'useImports';

    /**
     * @var UXNode
     */
    protected $uiSettings;

    /**
     * @var AbstractBundle[]
     */
    protected $bundles = [];

    /**
     * @var Configuration[]
     */
    protected $bundleConfigs = [];

    /**
     * ...
     */
    public function inject()
    {
        $this->project->on('save', [$this, 'doSave']);
        $this->project->on('preCompile', [$this, 'doPreCompile']);
        $this->project->on('makeSettings', [$this, 'doMakeSettings']);
        $this->project->on('updateSettings', [$this, 'doUpdateSettings']);
    }

    public function doSave()
    {
        foreach ($this->bundles as $env => $group) {
            /** @var AbstractBundle $bundle */
            foreach ($group as $bundle) {
                $type = get_class($bundle);
                $type = str::replace($type, '\\', '.');

                $config = $this->project->getIdeConfig("bundles/$type.conf");
                $config->set('env', $env);

                $bundle->onSave($this->project, $config);
            }
        }
    }

    public function doLoad()
    {
        $files = $this->project->getIdeFile("bundles/")->findFiles();

        foreach ($files as $file) {
            if (fs::ext($file) == '.conf' && fs::isFile($file)) {
                $config = $this->project->getIdeConfig("bundles/" . fs::name($file));

                $class = str::replace(fs::nameNoExt($file), '.', '\\');

                if (class_exists($class)) {
                    $bundle = new $class();

                    if ($bundle instanceof AbstractBundle) {
                        $this->bundleConfigs[get_class($bundle)] = $config;

                        $bundle->onLoad($this->project, $config);
                        $this->addBundle($config->get('env') ?: Project::ENV_ALL, $bundle);
                    }
                }
            }
        }
    }

    public function doPreCompile($env, callable $log = null)
    {
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

        $php = PhpProjectBehaviour::get();

        if ($php) {
            $php->clearGlobalUseImports();

            foreach ($allBundles as $bundle) {
                $config = $this->getBundleConfig($bundle);

                //if (!$config || $config->get(self::CONFIG_BUNDLE_KEY_USE_IMPORTS, true)) {
                    foreach ($bundle->getUseImports() as $useImport) {
                        $php->addGlobalUseImport($useImport);
                    }
               // }
            }
        }
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

        $groups = [(array) $this->bundles[$env]];

        if ($env != Project::ENV_ALL) {
            $groups[] = (array) $this->bundles[Project::ENV_ALL];
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

        return $bundle = new $class();
    }

    /**
     * @param string $env
     * @param string $class
     */
    public function addBundle($env, $class)
    {
        if (!$this->bundles[$env][$class]) {
            unset($this->bundles[Project::ENV_ALL][$class]);

            $this->bundles[$env][$class] = new $class();
        }
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

    public function doUpdateSettings(ProjectEditor $editor = null)
    {
        if ($this->uiSettings) {

        }
    }

    public function doMakeSettings(ProjectEditor $editor)
    {
        $ui = new UXHBox([new UXLabel('Пакеты: ')]);

        $editor->addSettingsPane($ui);
    }
}