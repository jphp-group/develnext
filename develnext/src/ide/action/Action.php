<?php
namespace ide\action;
use php\xml\DomElement;

/**
 * Class Action
 * @package ide\action
 */
class Action
{
    /**
     * @var AbstractActionType
     **/
    protected $type;

    protected $__level = 0;

    /**
     * @var string
     */
    protected $__contextClass = null;

    /**
     * @var string
     */
    protected $__contextMethod = null;

    /**
     * @param AbstractActionType $type
     */
    public function __construct(AbstractActionType $type)
    {
        $this->type = $type;
    }

    public function fillDefaults()
    {
        $type = $this->getType();

        if ($type instanceof AbstractSimpleActionType) {
            foreach ($type->attributeSettings() as $code => $settings) {
                if (isset($settings['def'])) {
                    $this->{$code} = $settings['def'];

                    if ($settings['defType']) {
                        $this->{"$code-type"} = $settings['defType'];
                    } else {
                        $this->{"$code-type"} = $type->attributes()[$code];
                    }
                }
            }
        }
    }

    public function imports()
    {
        $result = [];

        foreach ($this->type->imports($this) as $import) {
            if (is_array($import)) {
                $result[$import[0]] = $import;
            } else {
                $result[$import] = [$import];
            }
        }

        return $result;
    }

    public function get($field)
    {
        return $this->type->fetchFieldValue($this, $field, $this->{$field});
    }

    public function getFieldType($field)
    {
        return $this->{"$field-type"};
    }

    public function getDescription()
    {
        return $this->type->getDescription($this);
    }

    public function getTitle()
    {
        return $this->type->getTitle($this);
    }

    public function getIcon()
    {
        return $this->type->getIcon($this);
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $_level
     */
    public function setLevel($_level)
    {
        $this->__level = $_level;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->__level;
    }

    /**
     * @return string
     */
    public function getContextClass()
    {
        return $this->__contextClass;
    }

    /**
     * @param string $_contextClass
     */
    public function setContextClass($_contextClass)
    {
        $this->__contextClass = $_contextClass;
    }

    /**
     * @return string
     */
    public function getContextMethod()
    {
        return $this->__contextMethod;
    }

    /**
     * @param string $_contextMethod
     */
    public function setContextMethod($_contextMethod)
    {
        $this->__contextMethod = $_contextMethod;
    }
}