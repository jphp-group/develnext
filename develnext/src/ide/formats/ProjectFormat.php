<?php
namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\ProjectEditor;
use ide\editors\WelcomeEditor;
use ide\project\control\AbstractProjectControlPane;
use php\lib\fs;
use php\lib\reflect;

/**
 * @package ide\formats
 */
class ProjectFormat extends AbstractFormat
{

    /**
     * @var AbstractProjectControlPane[]
     */
    protected $controlPanes = [];

    /**
     * @param AbstractProjectControlPane $pane
     */
    public function addControlPane(AbstractProjectControlPane $pane)
    {
        $this->controlPanes[reflect::typeOf($pane)] = $pane;
    }

    /**
     * @param string $class
     */
    public function removeControlPane($class)
    {
        unset($this->controlPanes[$class]);
    }

    public function addControlPanes(array $panes)
    {
        foreach ($panes as $pane) $this->addControlPane($pane);
    }

    /**
     * @return \ide\project\control\AbstractProjectControlPane[]
     */
    public function getControlPanes()
    {
        return $this->controlPanes;
    }

    /**
     * @param $file
     *
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new ProjectEditor($file);
        $editor->setFormat($this);
        return $editor;
    }

    public function getIcon()
    {
        return 'icons/myProject16.png';
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return fs::ext($file) == 'dnproject';
    }

    /**
     * @param $any
     *
     * @return mixed
     */
    public function register($any)
    {

    }
}