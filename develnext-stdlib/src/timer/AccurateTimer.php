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
     * @var bool
     */
    protected $free = false;

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

           /* uiLater(function () {
                $timer = new AccurateTimer(5000, function () {
                    $count = sizeof(self::$timers);

                    $timers = self::$timers;

                    $freeCount = $stopCount = 0;

                    foreach ($timers as $timer) {
                        if ($timer->free) $freeCount++;
                        else if (!$timer->active) $stopCount++;
                    }

                    echo "AccurateTimer all count = $count, stopped = $stopCount, free = $freeCount\n";
                });
                $timer->start();
            }); */
        }
    }

    public static function __tick()
    {
        $now = Time::millis();

        $deleted = [];

        $accurateTimers = self::$timers;

        foreach ($accurateTimers as $key => $timer) {
            if (!$timer->active) {
                $deleted[] = $key;
                continue;
            }

            if ($now - $timer->_lastTick > $timer->interval) {
                $timer->_lastTick = $now;
                $timer->trigger();
            }
        }

        foreach ($deleted as $id) {
            unset(self::$timers[$id]);
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
        self::$timers[$this->id] = $this;

        $this->_lastTick = Time::millis();
        $this->active = true;
    }

    public function reset()
    {
        $this->_lastTick = Time::millis();
    }

    public function free()
    {
        $this->stop();
    }

    /**
     * @param $millis
     * @param callable $callback
     * @return AccurateTimer
     */
    static function executeAfter($millis, callable $callback)
    {
        $timer = new AccurateTimer($millis, function (AccurateTimer $timer) use ($callback) {
            uiLater(function () use ($callback) {
                $callback();
            });
            return true;
        });

        $timer->start();
        return $timer;
    }
}