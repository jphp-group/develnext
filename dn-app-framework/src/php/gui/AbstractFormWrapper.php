<?php
namespace php\gui;

use php\gui\event\UXEvent;
use php\gui\framework\AbstractForm;

/**
 * Class AbstractFormWrapper
 * @package php\gui
 *
 * @packages framework
 */
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
        switch ($event) {
            case 'construct':
                uiLater(function () use ($handler) {
                    $handler(UXEvent::makeMock($this->form));
                });
                return;
        }

        $this->form->on($event, $handler, $group);
    }
}