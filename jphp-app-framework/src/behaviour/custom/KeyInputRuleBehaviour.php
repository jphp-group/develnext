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
            $target->observer('text')->addListener(function ($old, $new) use ($target) {
                $changed = false;

                if (str::trim($this->allowedSymbols)) {
                    $allowedSymbols = str::trim($this->allowedSymbols);

                    $text = '';

                    for ($i = 0; $i < str::length($new); $i++) {
                        $character = $new[$i];

                        if (!$this->matchCase) {
                            $allowedSymbols = str::lower($allowedSymbols);
                            $character = str::lower($character);
                        }

                        if (str::contains($allowedSymbols, $character)) {
                            $text .= $character;
                        } else {
                            $changed = true;
                        }
                    }

                    $new = $text;
                }

                if ($this->maxLength > 0 && str::length($new) > $this->maxLength) {
                    $new = str::sub($new, 0, $this->maxLength);
                    $changed = true;
                }

                if ($changed) {
                    $target->text = $new;
                }
            });

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

                $length = str::length($target->text) - $target->selection['length'];

                if ($this->maxLength > 0 && $length >= $this->maxLength) {
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
        return 'keyInputRule';
    }
}