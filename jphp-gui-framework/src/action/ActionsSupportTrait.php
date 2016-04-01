<?php
namespace action;

use php\gui\framework\AbstractForm;
use php\gui\UXNode;

trait ActionsSupportTrait
{
    function form($name)
    {
        return app()->getForm($name);
    }

    /**
     * @return AbstractForm
     */
    function getContextForm()
    {
        return $this instanceof UXNode ? $this->form : null;
    }

    /**
     * @return string
     */
    function getContextFormName()
    {
        return $this->getContextForm() ? $this->getContextForm()->getName() : null;
    }

    /**
     * @param ...$args
     * @return mixed
     * @throws IllegalArgumentException
     */
    public function data(...$args)
    {
        static $data = [];

        $sizeof = sizeof($args);

        if ($sizeof == 1) {
            return $data[$args[0]];
        } elseif ($sizeof >= 2) {
            $old = $data[$args[0]];

            $data[$args[0]] = $args[1];
            return $old;
        }

        throw new IllegalArgumentException();
    }
}