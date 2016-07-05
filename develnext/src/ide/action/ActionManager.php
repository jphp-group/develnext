<?php
namespace ide\action;

use Files;
use ide\Ide;
use ide\utils\FileUtils;
use php\lib\fs;
use php\lib\Str;
use php\xml\DomElement;

class ActionManager
{
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
        $list = Ide::get()->getInternalList('.dn/actionTypes');

        foreach ($list as $className) {
            $type = new $className();

            $this->registerType($type);
        }
    }

    /**
     * @var AbstractActionType[]
     */
    protected $actionTypes = [];

    /**
     * @param $directory
     * @param callable $log
     * @param bool $withSourceMap
     */
    public function compile($directory, callable $log = null, $withSourceMap = false)
    {
        FileUtils::scan($directory, function ($filename) use ($log, $withSourceMap) {
            if (Str::equalsIgnoreCase(fs::ext($filename), 'source')) {
                $phpFile = FileUtils::stripExtension($filename);
                $actionFile = $phpFile . '.axml';

                if (!fs::exists($phpFile)) {
                    FileUtils::copyFile($filename, $phpFile);
                }

                if (fs::exists($actionFile)) {
                    $script = new ActionScript(null, $this);
                    $script->load($actionFile);

                    $count = $script->compile($filename, $phpFile, $withSourceMap);

                    if ($log && $count) {
                        $log($phpFile);
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

        $type = $this->actionTypes[Str::lower($tagName)];

        if (!$type) {
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

    public function registerType(AbstractActionType $type)
    {
        $this->actionTypes[Str::lower($type->getTagName())] = $type;
    }

    public function free()
    {
        $this->actionTypes = [];
    }
}