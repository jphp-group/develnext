<?php
namespace script\support;

use php\lib\Items;
use php\lib\Str;
use php\util\Flow;
use php\util\Scanner;

trait ScriptHelpers
{
    /**
     * @param $input
     * @return NodeHelper[]
     */
    protected function _fetchHelpers($input)
    {
        if ($input instanceof UXNode) {
            return [$input];
        } else if (is_array($input) && Items::first($input) instanceof UXNode) {
            return $input;
        }

        if (is_array($input)) {
            ;
        } else if (Str::contains($input, ',')) {
            $input = Str::split($input, ',');
        } else {
            $tmp = [];
            $sc = new Scanner($input, 'UTF-8');

            while ($sc->hasNextLine()) {
                $tmp[] = $sc->nextLine();
            }

            if ($tmp) {
                $input = $tmp;
            } else {
                $input = [$input];
            }
        }

        $result = [];

        foreach ($input as $string) {
            $out = new NodeHelper($this->_context, $string);

            if ($out->isValid()) {
                $result[] = $out;
            }
        }

        return $result;
    }

    protected function _eachHelper($input, callable $handle)
    {
        $nodes = $this->_fetchHelpers($input);

        foreach ($nodes as $node) {
            $handle($node);
        }
    }

    protected function _bindAction($node, $handle)
    {
        $this->_eachHelper($node, function (NodeHelper $node) use ($handle) {
            $node->bindAction($handle);
        });
    }

    protected function _adaptValue($input, $value)
    {
        $this->_eachHelper($input, function (NodeHelper $node) use ($value) {
            $node->adaptValue($value);
        });
    }
}