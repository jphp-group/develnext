<?php
namespace php\gui;

use php\gui\framework\AbstractForm;

class AbstractFormWrapper
{
    /**
     * @var AbstractForm
     */
    protected $form;

    /**
     * UXNodeWrapper constructor.
     *
     * @param $form
     */
    public function __construct(AbstractForm $form)
    {
        $this->form = $form;
    }

    /**
     * @param UXData $data
     */
    public function applyData(UXData $data)
    {
       // nop
    }

    /**
     * @param $event
     * @param callable $handler
     * @param $group
     */
    public function bind($event, callable $handler, $group)
    {
        $this->form->on($event, $handler, $group);
    }
}