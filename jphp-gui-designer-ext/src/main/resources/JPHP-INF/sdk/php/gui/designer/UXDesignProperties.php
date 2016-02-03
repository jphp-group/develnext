<?php
namespace php\gui\designer;

use php\gui\UXTitledPane;

/**
 * Class UXDesignProperties
 * @package php\gui\designer
 */
class UXDesignProperties
{
    /**
     * @var mixed
     */
    public $target;

    /**
     * @param callable|null $handler
     */
    public function onChange($handler)
    {
    }

    public function triggerChange()
    {
    }

    /**
     * @param $code
     * @param $title
     * @return UXTitledPane
     */
    public function addGroup($code, $title)
    {
    }

    /**
     * @param $code
     */
    public function removeGroup($code)
    {
    }

    /**
     * @param $code
     * @return UXTitledPane|null
     */
    public function getGroupPane($code)
    {
    }

    /**
     * @return UXTitledPane[]
     */
    public function getGroupPanes()
    {
    }

    /**
     * @param string $groupCode
     * @param string $code
     * @param string $name
     * @param UXDesignPropertyEditor $editor
     */
    public function addProperty($groupCode, $code, $name, UXDesignPropertyEditor $editor)
    {

    }

    /**
     * Update all values.
     */
    public function update()
    {
    }

    /**
     * @param $groupCode
     */
    public function updateOne($groupCode)
    {
    }
}