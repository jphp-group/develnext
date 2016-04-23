<?php
namespace php\gui\designer;

use php\gui\UXTableCell;

/**
 * Class UXDesignPropertyEditor
 * @package php\gui\designer
 */
abstract class UXDesignPropertyEditor
{
    /**
     * @readonly
     * @var string
     */
    public $groupCode;

    /**
     * @readonly
     * @var string
     */
    public $code;

    /**
     * @readonly
     * @var string
     */
    public $name;

    /**
     * @readonly
     * @var UXDesignProperties
     */
    public $designProperties;

    /**
     * @param UXTableCell $cell
     * @param bool $empty
     * @return mixed
     * @internal param UXDesignProperties $properties
     */
    abstract public function update(UXTableCell $cell, $empty);
}