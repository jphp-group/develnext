<?php
namespace ide\utils;


use php\lib\str;
use php\util\Scanner;

class StrUtils
{
    static function lineCount($text, $emptyIsLine = false)
    {
        $scanner = new Scanner($text, 'UTF-8');

        $result = 0;

        while ($scanner->hasNextLine()) {
            $scanner->nextLine();

            $result++;
        }

        if (str::endsWith($text, "\n") || str::endsWith($text, "\r")) {
            $result += 1;
        }

        if ($emptyIsLine && $result == 0 && !$text) {
            $result = 1;
        }

        return $result;
    }
}