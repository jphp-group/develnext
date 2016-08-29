<?php
namespace ide\action;

use Files;
use ide\Ide;
use ide\IdeException;
use ide\Logger;
use ide\utils\FileUtils;
use php\lib\fs;
use php\lib\reflect;
use php\lib\Str;
use php\time\Time;
use php\xml\DomElement;

class ActionManager
{
    /**
     * @var AbstractActionType[]
     */
    protected $actionTypes = [];

    /**
     * @var AbstractActionType[]
     */
    protected $actionTypeByTagName = [];

    protected $actionTypeGroups = [];

    /**
     * @var int
     */
    protected $lastUpdated = 0;

    public static function get()
    {
        static $instance;

        if (!$instance) {
            return $instance = new ActionManager();
        }

        return $instance;
    }

    function __construct()
    {
        //$this->registerInternalList('.dn/actionTypes');
    }

    /**
     * @param $directory
     * @param string $outDirectory
     * @param callable $log
     * @param bool $withSourceMap
     */
    public function compile($directory, $outDirectory, callable $log = null, $withSourceMap = false)
    {
        fs::scan($directory, function ($filename) use ($log, $directory, $outDirectory, $withSourceMap) {
            if (fs::ext($filename) == 'php') {
                $newFile = $outDirectory . "/" . FileUtils::relativePath($directory, $filename);
                $actionFile = $filename . '.axml';

                if (fs::exists($actionFile)) {
                    fs::ensureParent($newFile);

                    $script = new ActionScript(null, $this);
                    $script->load($actionFile);

                    if (!$script->isEmpty()) {
                        if (!fs::exists($newFile)) {
                            FileUtils::copyFile($filename, $newFile);
                        }

                        $count = $script->compile($newFile, null, $withSourceMap);

                        if ($log) {
                            $log(FileUtils::relativePath($directory, $filename) . " ($count)");
                        }
                    }
                }
            }
        });
    }

    /**
     * @param DomElement $element
     * @return Action|null
     */
    public function buildAction(DomElement $element)
    {
        $tagName = $element->getTagName();

        $type = $this->actionTypeByTagName[Str::lower($tagName)];

        if (!$type) {
            //Logger::error("Cannot find '$tagName' action type");

            return null;
        }

        $action = new Action($type, $element);
        $type->unserialize($action, $element);

        return $action;
    }

    /**
     * @return AbstractActionType[]
     */
    public function getActionTypes()
    {
        return $this->actionTypes;
    }

    /**
     * @param AbstractActionType|string $type
     * @throws IdeException
     */
    public function registerType($type)
    {
        if ($type instanceof AbstractActionType) {
            $this->actionTypes[reflect::typeOf($type, true)] = $type;
            $this->actionTypeByTagName[str::lower($type->getTagName())] = $type;
            $this->lastUpdated = Time::millis();
        } elseif (is_string($type)) {
            $this->registerType(new $type);
        } else {
            throw new IdeException("Invalid action type - $type");
        }
    }

    /**
     * @param string $class
     */
    public function unregisterType($class)
    {
        $class = str::lower($class);

        if ($type = $this->actionTypes[$class]) {
            unset($this->actionTypeByTagName[str::lower($type->getTagName())]);
            unset($this->actionTypes[$class]);

            $this->lastUpdated = Time::millis();
        }
    }

    public function free()
    {
        //$this->actionTypes = [];
    }

    public function lastUpdated()
    {
        return $this->lastUpdated;
    }
    /**
     * @param string $source resource path
     * @throws IdeException
     */
    public function registerInternalList($source)
    {
        $ones = Ide::get()->getInternalList($source);

        foreach ($ones as $one) {
            $this->registerType($one);
        }
    }

    /**
     * @param string $source
     * @throws IdeException
     */
    public function unregisterInternalList($source)
    {
        $ones = Ide::get()->getInternalList($source);

        foreach ($ones as $one) {
            $this->unregisterType($one);
        }
    }
}