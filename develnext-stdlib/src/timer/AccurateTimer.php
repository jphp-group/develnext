<?php
namespace timer;

use php\gui\animation\UXAnimationTimer;
use php\lib\str;
use php\time\Time;

/**
 * Class AccurateTimer
 * @package timer
 */
class AccurateTimer
{
    /**
     * @var AccurateTimer[]
     * */
    static protected $timers = [];

    /**
     * @var UXAnimationTimer
     */
    static protected $animTimer;

    /**
     * @var int
     */
    public $interval;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var int
     */
    public $_lastTick;

    /**
     * @var callable
     */
    private $handler;

    protected static function init()
    {
        if (!self::$animTimer) {
            self::$animTimer = new UXAnimationTimer([AccurateTimer::class, '__tick']);
            self::$animTimer->start();
        }
    }

    public static function __tick()
    {
        $now = Time::millis();

        foreach (self::$timers as $timer) {
            if ($now - $timer->_lastTick > $timer->interval) {
                $timer->_lastTick = $now;
                $timer->trigger();
            }
        }
    }

    /**
     * AccurateTimer constructor.
     * @param int $interval
     * @param callable $callback
     */
    public function __construct($interval, callable $callback)
    {
        self::init();
        $this->interval = $interval;
        $this->handler = $callback;
        $this->id = str::uuid();

        self::$timers[$this->id] = $this;
    }

    public function trigger()
    {
        $handler = $this->handler;

        if ($handler($this) === true) {
            $this->stop();
        }
    }

    public function stop()
    {
        $this->active = false;
    }

    public function start()
    {
        $this->active = true;
    }

    public function free()
    {
        $this->stop();

        unset(self::$timers[$this->id]);
    }
}