<?php
namespace ide\formats\form;

use php\gui\designer\UXDesignProperties;
use php\gui\UXImage;
use php\gui\UXNode;

/**
 * Class AbstractFormElement
 * @package ide\formats\form
 */
abstract class AbstractFormElement
{
    abstract public function isOrigin($any);

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return UXNode
     */
    abstract public function createElement();

    /**
     * @param UXDesignProperties $properties
     */
    abstract public function createProperties(UXDesignProperties $properties);

    /**
     * @return null|string|UXImage
     */
    public function getIcon()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getDefaultSize()
    {
        return [100, 100];
    }

    public function getGroup()
    {
        return 'Главное';
    }
}