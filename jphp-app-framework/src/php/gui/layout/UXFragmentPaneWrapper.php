<?php
namespace php\gui\layout;

use php\framework\Logger;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFragmentPane;
use php\gui\UXData;
use php\gui\UXNodeWrapper;
use php\lang\IllegalArgumentException;
use php\lib\str;

class UXFragmentPaneWrapper extends UXNodeWrapper
{
    public function applyData(UXData $data)
    {
        parent::applyData($data);

        /** @var UXFragmentPane $node */
        $node = $this->node;

        $value = $data->get('content');

        $node->data('--property-content-setter', function ($value) use ($node) {
            Logger::warn("Use AbstractForm::showInFragment() method instead of set 'content' property for fragment with id = '{$node->id}'!");

            $form = $value;

            if (!$form) {
                $node->applyFragment(null);
                return;
            }

            if (is_string($form)) {
                $form = app()->getNewForm($form);
            } else if ($form instanceof AbstractForm) {

            } else {
                Logger::error("Cannot load form '$value' for fragment with id = '{$node->id}', must be instance of " . AbstractForm::class);
            }

            if (!$form) {
                return;
            }

            $form->showInFragment($node);
        });

        $form = app()->getNewForm($value);

        if ($form instanceof AbstractForm) {
            $form->showInFragment($node);
        } else {
            Logger::error("Cannot load form '$value' for fragment with id = '{$node->id}', must be instance of " . AbstractForm::class);
        }
    }
}