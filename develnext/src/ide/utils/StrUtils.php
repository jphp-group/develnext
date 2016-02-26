<?php
namespace ide\utils;


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

        if ($emptyIsLine && $result == 0 && !$text) {
            $result = 1;
        }

        return $result;
    }
}