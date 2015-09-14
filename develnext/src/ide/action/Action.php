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
     * @param AbstractActionType $type
     */
    public function __construct(AbstractActionType $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     **/
    public function convertToCode()
    {
        return $this->type->convertToCode($this);
    }

    public function imports()
    {
        $result = [];

        foreach ($this->type->imports() as $import) {
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
}