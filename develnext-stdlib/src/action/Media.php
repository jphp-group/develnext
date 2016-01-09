<?php
namespace action;

use php\gui\UXMedia;
use php\gui\UXMediaPlayer;
use php\lib\str;
use script\MediaPlayerScript;

/**
 * Class Media
 * @package action
 */
class Media
{
    /**
     * @var MediaPlayerScript[]
     */
    static protected $players = [];

    /**
     * @param $id
     * @return MediaPlayerScript
     * @throws \Exception
     */
    static protected function fetchPlayer($id)
    {
        if ($id instanceof MediaPlayerScript) {
            return $id;
        }

        $player = self::$players[$id];

        if (!$player) {
            self::$players[$id] = $player = new MediaPlayerScript();
            $player->id = $id;
        }

        return $player;
    }

    static function open($file, $autoPlay = true, $id = 'general')
    {
        $player = static::fetchPlayer($id);
        $player->autoplay = $autoPlay;
        $player->open($file);
    }

    static function isStatus($status, $id = 'general')
    {
        $player = static::fetchPlayer($id);

        return $player->status == $status;
    }

    static function stop($id = 'general')
    {
        $player = static::fetchPlayer($id);
        $player->stop();
    }

    static function pause($id = 'general')
    {
        $player = static::fetchPlayer($id);
        $player->pause();
    }

    static function play($id = 'general')
    {
        $player = static::fetchPlayer($id);
        $player->play();
    }
}