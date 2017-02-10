<?php
namespace php\gui\designer;

use php\gui\UXControl;
use php\gui\UXNode;
use php\gui\UXPopupWindow;
use php\io\File;

class UXDirectoryTreeValue
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $text;

    /**
     * @var UXNode
     */
    public $icon;

    /**
     * @var UXNode
     */
    public $expandIcon;

    /**
     * @var bool
     */
    public $folder = false;

    /**
     * UXDirectoryTreeValue constructor.
     * @param string $path
     * @param string $code
     * @param string $text
     * @param UXNode $icon
     * @param UXNode $expandIcon
     * @param bool $folder
     */
    public function __construct($path, $code, $text, UXNode $icon, UXNode $expandIcon, $folder)
    {
        $this->path = $path;
        $this->code = $code;
        $this->text = $text;
        $this->icon = $icon;
        $this->expandIcon = $expandIcon;
        $this->folder = $folder;
    }


}