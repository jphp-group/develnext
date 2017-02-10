<?php
namespace php\gui\designer;

use php\gui\UXTreeView;


/**
 * Class UXDesigner
 * @package php\gui\misc
 */
class UXDirectoryTreeView extends UXTreeView
{
    /**
     * @var UXAbstractDirectoryTreeSource
     */
    public $treeSource;

    /**
     * UXDirectoryTreeView constructor.
     * @param string $directory (optional)
     */
    public function __construct($directory)
    {
    }
}