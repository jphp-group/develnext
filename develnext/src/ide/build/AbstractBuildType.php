<?php
namespace ide\build;
use ide\forms\BuildTypeConfigForm;
use ide\Ide;
use ide\project\Project;
use ide\utils\Json;
use php\format\JsonProcessor;
use php\format\ProcessorException;
use php\io\IOException;
use php\io\Stream;
use php\lib\Str;

/**
 * Class AbstractBuildType
 * @package ide\build
 */
abstract class AbstractBuildType
{
    /**
     * @return string
     */
    abstract function getName();

    /**
     * @return string
     */
    abstract function getDescription();

    /**
     * @return mixed
     */
    abstract function getIcon();

    /**
     * @param Project $project
     *
     * @return string
     */
    abstract function getBuildPath(Project $project);

    /**
     * @return string path for fxml
     */
    public function getConfigForm() {
        return null;
    }

    /**
     * @return array
     */
    public function getDefaultConfig()
    {
        return [];
    }

    /**
     * @param Project $project
     *
     * @param bool $finished
     *
     * @return mixed
     */
    abstract function onExecute(Project $project, $finished = true);

    /**
     * @return array
     */
    public function getConfig()
    {
        $project = Ide::get()->getOpenedProject();

        $json = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);
        $path = $project->getIdeDir() . "/" . Str::replace(get_class($this), '\\', '.') . ".json";

        try {
            return (array) $json->parse(Stream::getContents($path));
        } catch (IOException $e) {
            // nop
        } catch (ProcessorException $e) {
            // nop
        }

        return (array) $this->getDefaultConfig();
    }

    private function saveConfig(BuildTypeConfigForm $dialog)
    {
        $project = Ide::get()->getOpenedProject();

        $path = $project->getIdeDir() . "/" . Str::replace(get_class($this), '\\', '.') . ".json";

        Json::toFile($path, $dialog->getData());
    }

    private function loadConfig(BuildTypeConfigForm $dialog)
    {
        $dialog->setData($this->getConfig());
    }

    function showConfigDialog()
    {
        $dialog = new BuildTypeConfigForm($this->getConfigForm());
        $this->loadConfig($dialog);
        $dialog->loadBindings($this);
        $dialog->title = $this->getName();

        if ($dialog->showDialog()) {
            $this->saveConfig($dialog);
            $this->setShowConfig($dialog->showConfigCheckbox->selected);
            return true;
        }

        return false;
    }

    function fetchConfig()
    {
        $configForm = $this->getConfigForm();

        if ($configForm) {

            if (static::isShowConfig()) {
                $dialog = new BuildTypeConfigForm($configForm);
                $this->loadConfig($dialog);
                $dialog->title = $this->getName();

                $dialog->loadBindings($this);

                if ($dialog->showDialog()) {
                    $this->saveConfig($dialog);
                    $this->setShowConfig($dialog->showConfigCheckbox->selected);
                    return true;
                }

                return false;
            }
        }

        return true;
    }

    static function isShowConfig()
    {
        return (bool) Ide::get()->getUserConfigValue(__CLASS__ . '.showConfig', true);
    }

    static function setShowConfig($value)
    {
        Ide::get()->setUserConfigValue(__CLASS__ . '.showConfig', $value);
    }
}