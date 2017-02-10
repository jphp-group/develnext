<?php
namespace php\gui;

use php\io\IOException;

/**
 * Class UXDatePickerWrapper
 * @package php\gui
 *
 * @packages framework
 */
class UXDatePickerWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        /** @var UXDatePicker $node */
        $node = $this->node;

        $node->format = $data->get('format');
        $node->value = $data->get('value');
    }
}