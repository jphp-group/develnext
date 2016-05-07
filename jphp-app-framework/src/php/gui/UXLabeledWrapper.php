<?php
namespace php\gui;

use php\framework\Logger;

abstract class UXLabeledWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        if ($data->has('graphic')) {
            try {
                $graphic = new UXImageView();
                $graphic->image = new UXImage('res://' . $data->get('graphic'));

                $this->node->graphic = $graphic;
            } catch (\Exception $e) {
                Logger::error('Cannot load graphic image: "' . $data->get('graphic') . '", component = ' . $this->node->id);
            }
        }
    }
}