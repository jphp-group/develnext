<?php
namespace php\gui;

/**
 * Class UXTabPane
 * @package php\gui
 */
class UXTabPane extends UXControl
{
    /**
     * @var UXList
     */
    public $tabs;

    /**
     * @var UXTab
     */
    public $selectedTab;

    /**
     * @var int
     */
    public $selectedIndex = -1;

    /**
     * @var string
     */
    public $tabClosingPolicy;

    /**
     * @var string
     */
    public $side;

    /**
     * @param UXTab $tab
     */
    public function selectTab(UXTab $tab) { }

    /**
     * ...
     */
    public function selectFirstTab() {}

    /**
     * ...
     */
    public function selectNextTab() {}

    /**
     * ...
     */
    public function selectLastTab() {}

    /**
     * ...
     */
    public function selectPreviousTab() {}

    /**
     * ...
     */
    public function clearSelection() {}
}