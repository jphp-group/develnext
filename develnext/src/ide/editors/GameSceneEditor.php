<?php
namespace ide\editors;

use ide\formats\GuiFormDumper;

/**
 * Class GameSceneEditor
 * @package ide\editors
 */
class GameSceneEditor extends FormEditor
{
    /**
     * GameSceneEditor constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct($file, new GuiFormDumper([]));
    }

    public function load()
    {
        $this->loadOthers();
    }
}