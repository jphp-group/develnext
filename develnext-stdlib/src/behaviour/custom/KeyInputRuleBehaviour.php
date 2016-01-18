<?php
namespace behaviour\custom;

use php\gui\event\UXKeyEvent;
use php\gui\framework\behaviour\custom\AbstractBehaviour;
use php\gui\UXTextField;
use php\gui\UXTextInputControl;
use php\lib\str;

/**
 * Class KeyInputRuleBehaviour
 * @package behaviour\custom
 */
class KeyInputRuleBehaviour extends AbstractBehaviour
{
    /**
     * @var string
     */
    public $allowedSymbols = '0123456789';

    /**
     * @var int
     */
    public $maxLength = 0;

    /**
     * @var bool
     */
    public $matchCase = false;

    /**
     * @param mixed $target
     */
    protected function applyImpl($target)
    {
        if ($target instanceof UXTextInputControl) {
            $target->on('keyPress', function (UXKeyEvent $e) use ($target) {
                switch ($e->codeName) {
                    case 'Enter':
                    case 'Backspace':
                    case 'Delete':
                    case 'Left':
                    case 'Right':
                    case 'Up':
                    case 'Down':
                    case 'Esc':
                        return;
                }

                if ($this->maxLength > 0 && str::length($target->text) >= $this->maxLength) {
                    $e->consume();
                }

                if (str::trim($this->allowedSymbols)) {
                    $allowedSymbols = str::trim($this->allowedSymbols);
                    $character = $e->character;

                    if (!$this->matchCase) {
                        $allowedSymbols = str::lower($allowedSymbols);
                        $character = str::lower($character);
                    }

                    if (!str::contains($allowedSymbols, $character)) {
                        $e->consume();
                    }
                }
            });
        }
    }

    public function getCode()
    {
        return 'KeyInputRule';
    }
}