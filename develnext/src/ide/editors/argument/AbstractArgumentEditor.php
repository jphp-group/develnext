<?php
namespace ide\editors\argument;
use ide\Ide;
use php\gui\UXNode;

/**
 * Class AbstractArgumentEditor
 * @package ide\editors\argument
 */
abstract class AbstractArgumentEditor
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $valueType;

    /**
     * @var mixed
     */
    protected $userData;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * AbstractArgumentEditor constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }


    public function setValue($value, $type)
    {
        $this->value = $value;
        $this->valueType = $type;
    }

    public function isInline()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getValueType()
    {
        return $this->valueType;
    }

    /**
     * @return string
     */
    abstract public function getCode();

    /**
     * @param null $label
     * @return UXNode
     */
    abstract public function makeUi($label = null);


    private static $editors = [];

    public static function register(AbstractArgumentEditor $editor)
    {
        self::$editors[$editor->getCode()] = $editor;
    }

    /**
     * @param $code
     * @return AbstractArgumentEditor
     * @throws \Exception
     */
    public static function get($code)
    {
        if (!self::$editors) {
            $list = Ide::get()->getInternalList('.dn/argumentValueEditors');

            foreach ($list as $class) {
                self::register(new $class());
            }
        }

        if ($editor = self::$editors[$code]) {
            return $editor;
        }

        throw new \Exception("Unable to find '$code' argument editor");
    }

    /**
     * @param $type
     * @param array $options
     * @return AbstractArgumentEditor
     * @throws \Exception
     */
    public static function make($type, array $options = [])
    {
        $editor = self::get($type);

        $class = get_class($editor);

        return new $class($options);
    }

    abstract public function requestUiFocus();

    public function setUserData($userData)
    {
        $this->userData = $userData;
    }
}