<?php
namespace php\gui\framework;

use php\gui\UXForm;
use php\gui\UXLoader;
use php\io\Stream;
use php\lib\String;

/**
 * Class AbstractForm
 * @package php\gui\framework
 */
abstract class AbstractForm
{
    const DEFAULT_PATH = 'res://.forms/';

    protected $_origin;

    public function __construct(UXForm $form = null)
    {
        $this->_origin = $form === null ? new UXForm() : $form;

        $this->loadDesign();
        $this->loadBindings();
    }

    protected function loadDesign()
    {
        $loader = new UXLoader();
        $in = Stream::of(static::DEFAULT_PATH . String::replace(get_class($this), '\\', '/') . '.fxml');

        try {
            $this->_origin->layout = $loader->load($in);
        } finally {
            $in->close();
        }
    }

    protected function loadBindings()
    {

    }

    public function __get($name)
    {
        return $this->_origin->layout->lookup('#' . $name);
    }

    public function __isset($name)
    {
        return !!$this->_origin->layout->lookup('#' . $name);
    }

    public function getOrigin()
    {
        return $this->_origin;
    }

    public function show()
    {
        $this->_origin->show();
    }

    public function hide()
    {
        $this->_origin->hide();
    }
}