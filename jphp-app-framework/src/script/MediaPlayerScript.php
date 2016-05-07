<?php
namespace script;

use behaviour\StreamLoadableBehaviour;
use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\TextableBehaviour;
use php\gui\framework\behaviour\ValuableBehaviour;
use php\gui\UXMedia;
use php\gui\UXMediaPlayer;
use php\lib\str;

/**
 * Class MediaPlayerScript
 * @package script
 *
 * @property int $position
 * @property int $positionMs
 * @property double $volume
 * @property double $rate
 * @property string $source
 * @property bool $loop
 * @property bool $mute
 * @property string $status
 */
class MediaPlayerScript extends AbstractScript implements TextableBehaviour, ValuableBehaviour, StreamLoadableBehaviour
{
    /**
     * @var bool
     */
    public $autoplay;

    /**
     * @var string
     */
    private $_source;

    /**
     * @var double
     */
    private $_volume = 1.0;

    /**
     * @var double
     */
    private $_rate = 1.0;

    /**
     * @var bool
     */
    private $_mute = false;

    /**
     * @var float
     */
    private $_balance = 0.0;

    /**
     * @var bool
     */
    private $_loop = false;

    /**
     * @var UXMediaPlayer
     */
    protected $_player;

    /**
     * @var TimerScript
     */
    private $_stateTimer;

    /**
     * MediaPlayerScript constructor.
     * @param string $source
     */
    public function __construct($source = null)
    {
        $this->setSource($source);

        $this->_stateTimer = new TimerScript(300, true, function () {
            $this->triggerTick();
        });
        $this->_stateTimer->start();
    }

    public function _init($reopen = true)
    {
        if ($this->disabled) {
            return;
        }

        if (!$reopen && $this->_player) {
            return;
        }

        if ($this->_player) {
            $this->_player->stop();
        }

        if (!$this->_source) {
            $this->_player = null;
            return;
        }

        $source = $this->getSource();

        if (str::startsWith($source, "res://")) {
            $media = UXMedia::createFromResource("/" . str::sub($source, 6));
        } elseif (str::startsWith($source, "http://") || str::startsWith($source, "https://") || str::startsWith($source, "ftp://")) {
            $media = UXMedia::createFromUrl($source);
        } else {
            $media = new UXMedia($source);
        }

        $this->_player = new UXMediaPlayer($media);
        $this->setRate($this->_rate);
        $this->setVolume($this->_volume);
        $this->setMute($this->_mute);
        $this->setLoop($this->_loop);
        $this->setBalance($this->_balance);
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        if ($this->autoplay) {
            $this->play();
        }
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $reopen = $source != $this->_source;
        $this->_source = $source;

        if ($reopen) {
            $this->_init();
        }

        $this->setPosition(0);

        if ($this->autoplay && $source) {
            $this->play();
        }
    }

    /**
     * @return float
     */
    public function getVolume()
    {
        return $this->_volume;
    }

    /**
     * @param float $volume
     */
    public function setVolume($volume)
    {
        $this->_volume = $volume;

        if ($this->_player) {
            $this->_player->volume = $volume;
        }
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->_rate;
    }

    /**
     * @param float $rate
     */
    public function setRate($rate)
    {
        $this->_rate = $rate;

        if ($this->_player) {
            $this->_player->rate = $rate;
        }
    }

    /**
     * @return boolean
     */
    public function getMute()
    {
        return $this->_mute;
    }

    /**
     * @param boolean $mute
     */
    public function setMute($mute)
    {
        $this->_mute = $mute;

        if ($this->_player) {
            $this->_player->mute = $mute;
        }
    }

    /**
     * @return boolean
     */
    public function getLoop()
    {
        return $this->_loop;
    }

    /**
     * @param boolean $loop
     */
    public function setLoop($loop)
    {
        $this->_loop = $loop;

        if ($this->_player) {
            $this->_player->cycleCount = $loop ? -1 : 1;
        }
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->_balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance($balance)
    {
        $this->_balance = $balance;

        if ($this->_player) {
            $this->_player->balance = $balance;
        }
    }

    public function getStatus()
    {
        return $this->_player ? $this->_player->status : 'UNKNOWN';
    }

    public function setPosition($value)
    {
        if ($this->_player) {
            $this->_player->currentTimeAsPercent = $value;
        }
    }

    public function getPosition()
    {
        return $this->_player ? $this->_player->currentTimeAsPercent : 0;
    }

    public function setPositionMs($value)
    {
        if ($this->_player) {
            $this->_player->currentTime = $value;
        }
    }

    public function getPositionMs()
    {
        return $this->_player ? $this->_player->currentTime : 0;
    }

    /**
     * @param $file
     */
    public function open($file)
    {
        $this->setSource($file);
    }

    public function stop()
    {
        if ($this->_player) {
            $this->_player->stop();
        }
    }

    public function pause()
    {
        if ($this->_player) {
            $this->_player->pause();
        }
    }

    public function play()
    {
        if ($this->_player) {
            $this->_player->play();
        }
    }

    protected function triggerTick()
    {
        static $lastStatus;

        if ($this->disabled) {
            $this->stop();
            $this->setSource(null);
            return;
        }

        $status = $this->getStatus();

        if ($status == 'PLAYING') {
            $this->trigger('play');
        }

        if ($status != $lastStatus) {
            switch ($status) {
                case 'HALTED':
                    $this->trigger('error');
                    break;
                case 'STOPPED':
                    $this->trigger('stop');
                    break;
                case 'PAUSED':
                    $this->trigger('pause');
                    break;
            }
        }

        $lastStatus = $this->getStatus();
    }

    function getObjectText()
    {
        return $this->source;
    }

    /**
     * @param $path
     * @return mixed
     */
    function loadContentForObject($path)
    {
        return $path;
    }

    /**
     * @param $content
     * @return mixed
     */
    function applyContentToObject($content)
    {
        $this->setSource($content);
    }

    function getObjectValue()
    {
        return $this->positionMs;
    }

    function setObjectValue($value)
    {
        return $this->positionMs = $value;
    }

    function appendObjectValue($value)
    {
        $this->positionMs += $value;
    }
}