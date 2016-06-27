<?php
namespace ide\utils;


use php\time\Time;

class TimeUtils
{
    static function getUpdateAt($time)
    {
        if (!$time) {
            return "Неизвестная дата";
        }

        $now = Time::now();
        $time = $time instanceof Time ? $time : new Time($time);

        $date = $time->toString('dd.MM.yyyy');

        $days = (int) (($now->getTime() - $time->getTime()) / 24 / 60 / 60 / 1000);

        switch ($days) {
            case 0:
                $date = "Сегодня";
                break;
            case 1:
                $date = "Вчера";
                break;
            case 2:
                $date = "Позавчера";
                break;
        }

        return "$date, в {$time->toString('HH:mm')}";
    }
}