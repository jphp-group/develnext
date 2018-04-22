<?php
namespace php\gui;

use php\framework\Logger;
use php\io\IOException;

class UXImageAreaWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        $image = null;

        if ($data->get('image')) {
            try {
                $this->node->image = new UXImage('res://' . $data->get('image'));
            } catch (\Exception $e) {
                Logger::error('Cannot load image: "' . $data->get('image') . '", component = ' . $this->node->id);
            }
        }

        if ($data->get('hoverImage')) {
            try {
                $this->node->hoverImage = new UXImage('res://' . $data->get('hoverImage'));
            } catch (\Exception $e) {
                Logger::error('Cannot load hover image: "' . $data->get('hoverImage') . '", component = ' . $this->node->id);
            }
        }

        if ($data->get('clickImage')) {
            try {
                $this->node->clickImage = new UXImage('res://' . $data->get('clickImage'));
            } catch (\Exception $e) {
                Logger::error('Cannot load click image: "' . $data->get('clickImage') . '", component = ' . $this->node->id);
            }
        }
    }
}