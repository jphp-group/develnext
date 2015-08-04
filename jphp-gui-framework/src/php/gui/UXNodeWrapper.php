<?php
namespace php\gui;

class UXNodeWrapper
{
    /**
     * @var UXNode
     */
    protected $node;

    /**
     * UXNodeWrapper constructor.
     *
     * @param $node
     */
    public function __construct(UXNode $node)
    {
        $this->node = $node;
    }

    /**
     * @param UXData $data
     */
    public function applyData(UXData $data)
    {
        if ($data->has('enabled')) {
            $this->node->enabled = $data->get('enabled');
        }

        if ($data->has('visible')) {
            $this->node->visible = $data->get('visible');
        }
    }
}