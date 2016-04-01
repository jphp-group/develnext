<?php
namespace script\support;


use php\gui\UXButtonBase;
use php\gui\UXChoiceBox;
use php\gui\UXComboBox;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageView;
use php\gui\UXLabeled;
use php\gui\UXList;
use php\gui\UXListView;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXTextArea;
use php\gui\UXTextField;
use php\gui\UXTextInputControl;
use php\gui\UXWindow;
use php\lib\Items;
use php\lib\Str;
use php\util\Flow;
use php\util\Scanner;

class NodeHelper {
    /** @var UXNode */
    public $root;

    /** @var array */
    public $options;

    /**
     * NodeHelper constructor.
     * @param mixed $context
     * @param string $input
     */
    public function __construct($context, $input)
    {
        $input = Str::trim($input);
        $k = Str::pos($input, '[');

        if ($k > -1 && Str::endsWith($input, ']')) {
            $options = Str::sub($input, $k + 1, Str::length($input) - 1);
            $input = Str::sub($input, 0, $k);

            $out = $context->{Str::trim($input)};

            foreach (Str::split($options, ',') as $line) {
                $line = Str::trim($line);

                if (Str::contains($line, '=')) {
                    list($key, $value) = Flow::of(Str::split($line, '=', 2))->map(Str::class . '::trim')->toArray();
                    $this->options[$key] = $value;
                } else {
                    $this->options[$line] = true;
                }
            }
        } else {
            $out = $input ? $context->{$input} : $context;
        }

        $this->root = $out;
    }

    public function isValid()
    {
        return !!$this->root;
    }

    public function bindAction(callable $handle)
    {
        $root = $this->root;

        if ($root instanceof UXNode) {
            if ($root instanceof UXButtonBase || $root instanceof UXComboBox || $root instanceof UXMenuItem) {
                $root->on('action', $handle, 'ext');
            } else {
                $root->on('click', $handle, 'ext');
            }
        }
    }

    public function adaptValue($value)
    {
        $node = $this->root;

        if ($node instanceof UXLabeled || $node instanceof UXTextField) {
            $node->text = is_array($value) ? Items::first($value) : $value;
        } else if ($node instanceof UXForm) {
            $node->title = is_array($value) ? Items::first($value) : $value;
        } elseif ($node instanceof UXTextArea) {
            if (!$this->options['+']) {
                $node->text = '';
            }

            if (!is_array($value)) $value = [$value];

            foreach ($value as $line) {
                $node .= "$line\n";
            }
        } elseif ($node instanceof UXImageView) {
            if (is_array($value)) {
                $value = Items::first($value);
            }

            try {
                $node->image = new UXImage($value);
            } catch (\Exception $e) {
                $node->image = null;
                // nop
            }
        } elseif ($node instanceof UXListView || $node instanceof UXComboBox || $node instanceof UXChoiceBox) {
            if (!$this->options['+']) {
                $node->items->clear();
            }

            if (is_array($value)) {
                $node->items->addAll($value);
            } else {
                $node->items->add($value);
            }
        }
    }
}