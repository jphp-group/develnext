<?php
namespace php\gui;

use php\gui\event\UXEvent;

class UXWebViewWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        /** @var UXWebView $node */
        $node = $this->node;

        if ($data->has('contextMenuEnabled')) {
            $node->contextMenuEnabled = $data->get('contextMenuEnabled');
        }

        if ($data->has('url')) {
            UXApplication::runLater(function () use ($node, $data) {
                $node->engine->load($data->get('url'));
            });
        }
    }

    public function bind($event, callable $handler, $group)
    {
        /** @var UXWebView $node */
        $node = $this->node;

        $node->engine->watchState(function (UXWebEngine $self, $old, $new) use ($event, $handler) {
            $callback = function () use ($handler) {
                UXApplication::runLater(function () use ($handler) {
                    $handler(UXEvent::makeMock($this->node));
                });
            };

            switch ($event) {
                case 'load':
                    if ($new == 'SUCCEEDED') $callback();
                    break;

                case 'running':
                    if ($new == 'RUNNING') $callback();
                    break;

                case 'fail':
                    if ($new == 'FAILED') $callback();
            }
        });
    }
}