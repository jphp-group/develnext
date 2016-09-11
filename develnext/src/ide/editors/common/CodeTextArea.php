<?php
namespace ide\editors\common;

use ide\utils\Json;
use php\format\JsonProcessor;
use php\gui\designer\UXSyntaxTextArea;
use php\gui\UXApplication;
use php\gui\UXClipboard;
use php\gui\UXDialog;
use php\io\ResourceStream;
use php\lib\Str;

class CodeTextArea extends UXSyntaxTextArea
{
    /**
     * CodeTextArea constructor.
     * @param $mode
     * @param array $options
     */
    public function __construct($mode, array $options = [])
    {
        parent::__construct();

        $this->syntaxStyle = "text/$mode";
    }

    public function getValue()
    {
        return $this->text;
    }
    
    public function setValue($value)
    {
        $this->text = $value;
    }
}