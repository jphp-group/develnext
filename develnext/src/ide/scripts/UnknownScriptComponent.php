<?php
namespace ide\scripts;


class UnknownScriptComponent extends AbstractScriptComponent
{
    /**
     * @var string
     */
    private $type;

    /**
     * UnknownScriptComponent constructor.
     * @param $type
     */
    public function __construct($type)
    {
        parent::__construct();
        $this->type = $type;
    }

    public function getIcon()
    {
        return 'icons/warning16.png';
    }

    public function isOrigin($any)
    {
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Unknown";
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function getDescription()
    {

    }
}