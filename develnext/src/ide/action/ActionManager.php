<?php
namespace ide\action;

use Files;
use ide\Ide;
use ide\utils\FileUtils;
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
     */
    public function compile($directory, callable $log = null)
    {
        FileUtils::scan($directory, function ($filename) use ($log) {
            if (Str::equalsIgnoreCase(FileUtils::getExtension($filename), 'source')) {
                $phpFile = FileUtils::stripExtension($filename);
                $actionFile = $phpFile . '.axml';

                FileUtils::copyFile($filename, $phpFile);

                if (Files::exists($actionFile)) {
                    $script = new ActionScript(null, $this);
                    $script->load($actionFile);
                    $script->compile($filename, $phpFile);

                    if ($log) {
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
}