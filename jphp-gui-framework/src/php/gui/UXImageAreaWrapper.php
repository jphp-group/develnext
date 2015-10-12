<?php
namespace php\gui;

use php\io\IOException;

class UXImageAreaWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        $image = null;

        if ($data->has('image')) {
            try {
                $this->node->image = new UXImage('res://' . $data->get('image'));
            } catch (\Exception $e) {
                ;
            }
        }

        if ($data->has('hoverImage')) {
            try {
                $this->node->hoverImage = new UXImage('res://' . $data->get('hoverImage'));
            } catch (\Exception $e) {
                ;
            }
        }

        if ($data->has('clickImage')) {
            try {
                $this->node->clickImage = new UXImage('res://' . $data->get('clickImage'));
            } catch (\Exception $e) {
                ;
            }
        }
    }
}