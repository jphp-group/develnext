<?php
namespace php\gui;

use php\io\IOException;

class UXImageViewWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        $image = null;

        if ($data->has('image')) {
            try {
                $image = $this->node->image = new UXImage('res://' . $data->get('image'));
            } catch (\Exception $e) {
                ;
            }
        }

        if ($data->has('hoverImage')) {
            $this->node->on('mouseEnter', function () use ($image, $data) {
                try {
                    $this->node->image = new UXImage('res://' . $data->get('hoverImage'));
                } catch (IOException $e) {

                }
            }, __CLASS__);

            $this->node->on('mouseExit', function () use ($image) {
                $this->node->image = $image;
            }, __CLASS__);
        }

        if ($data->has('clickImage')) {
            $curImage = null;

            $this->node->on('mouseDown', function () use (&$curImage, $data) {
                try {
                    $curImage = $this->node->image;
                    $this->node->image = new UXImage('res://' . $data->get('clickImage'));
                } catch (IOException $e) {

                }
            }, __CLASS__);

            $this->node->on('mouseUp', function () use (&$curImage) {
                $this->node->image = $curImage;
            }, __CLASS__);
        }
    }
}