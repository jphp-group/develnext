<?php
namespace php\gui\dock;

use php\gui\layout\UXStackPane;

/**
 * Class UXDockPane
 * @package php\gui\dock
 */
class UXDockPane extends UXStackPane
{
    /**
     * @var bool
     */
    public $exclusive;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param string $filename
     */
    public function loadPreference($filename)
    {
    }

    /**
     * @param string $filename
     */
    public function storePreference($filename)
    {
    }
}