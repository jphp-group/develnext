<?php
namespace php\gui;

/**
 * Class UXListViewWrapper
 * @package php\gui
 *
 * @property UXListView $node
 */
class UXListViewWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        if ($data->has('multipleSelection')) {
            $this->node->multipleSelection = $data->get('multipleSelection');
        }
    }
}