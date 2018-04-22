<?php
namespace script;

use action\Score;
use behaviour\SetTextBehaviour;
use behaviour\StreamLoadableBehaviour;
use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\lib\fs;

/**
 * Class ScoreScript
 * @package script
 *
 * @packages framework
 */
class ScoreScript extends AbstractScript implements SetTextBehaviour, ValuableBehaviour, StreamLoadableBehaviour
{
    /**
     * @var string
     */
    public $name = 'global';

    /**
     * @var int
     */
    public $initialValue = 0;

    
    private $bEventUid, $aEventUid;

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        $this->reset();

        $this->bEventUid = Score::bind('beforeChange', function ($name, $old, $new) {
            if ($name == ($this->name ?: $this->id)) {
                $e = $this->trigger('beforeChange', ['name' => $name, 'oldValue' => $old, 'newValue' => $new]);

                if ($e && $e->isConsumed()) {
                    return false;
                }
            }

            return true;
        });

        $this->aEventUid = Score::bind('afterChange', function ($name, $old, $new) {
            if ($name == ($this->name ?: $this->id)) {
                $this->trigger('afterChange', ['name' => $name, 'oldValue' => $old, 'newValue' => $new]);
            }
        });
    }

    /**
     * Destroy component.
     */
    public function free()
    {
        parent::free();

        Score::off('beforeChange', $this->bEventUid);
        Score::off('afterChange', $this->aEventUid);
    }

    /**
     * Score value.
     * --RU--
     * Значение счета.
     * @return int
     */
    public function getValue()
    {
        return Score::get($this->name ?: $this->id);
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        Score::set($this->name ?: $this->id, $value);
    }

    /**
     * Set score to initial value.
     * --RU--
     * Установить счет в начальное значение.
     */
    public function reset()
    {
        Score::set($this->name ?: $this->id, $this->initialValue);
    }

    function setTextBehaviour($text)
    {
        $this->setValue($text);
    }

    function appendTextBehaviour($text)
    {
        $this->setValue($this->getValue() + (int) $text);
    }

    function getObjectValue()
    {
        return $this->getValue();
    }

    function setObjectValue($value)
    {
        $this->setValue($value);
    }

    function appendObjectValue($value)
    {
        $this->setValue($this->getValue() + $value);
    }

    /**
     * @param $path
     * @return mixed
     */
    function loadContentForObject($path)
    {
        return fs::get($path);
    }

    /**
     * @param $content
     * @return mixed
     */
    function applyContentToObject($content)
    {
        $this->setValue($content);
    }
}