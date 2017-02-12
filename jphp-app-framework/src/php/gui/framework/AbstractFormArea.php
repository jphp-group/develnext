<?php
namespace php\gui\framework;

use php\gui\layout\UXAnchorPane;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\io\Stream;
use php\lib\reflect;
use php\lib\str;

/**
 * Class AbstractFormArea
 * @package php\gui\framework
 *
 * @packages framework
 */
abstract class AbstractFormArea extends UXAnchorPane
{
    /**
     * AbstractFormArea
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadDesign();
        $this->loadBinds($this);
    }

    public function showPreloader($text = '')
    {
        $this->hidePreloader();

        $preloader = new Preloader($this, $text);
        $preloader->show();
    }

    public function hidePreloader()
    {
        Preloader::hidePreloader($this);
    }

    /**
     * @param object $handler
     */
    public function loadBinds($handler)
    {
        $binder = new EventBinder($this, $handler);
        $binder->setLookup(function (UXNode $context, $id) {
            return $this->lookup("#$id");
        });

        $binder->load();
    }

    /**
     * @return UXNode
     */
    protected function loadDesign()
    {
        $loader = new UXLoader();
        $ui = $loader->load(Stream::of($this->getResourcePath() . '.fxml'));

        UXAnchorPane::setAnchor($ui, 0);
        UXAnchorPane::setAnchor($this, 0);

        $this->add($ui);

        return $ui;
    }

    protected function getResourcePath()
    {
        return 'res://' . str::replace(reflect::typeOf($this), '\\', '/');
    }

    /**
     * @param $name
     * @return UXNode
     */
    public function __get($name)
    {
        return $this->lookup("#$name");
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return !!$this->lookup("#$name");
    }
}