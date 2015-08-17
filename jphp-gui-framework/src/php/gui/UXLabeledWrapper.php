<?php
namespace php\gui;

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
                ;
            }
        }
    }
}