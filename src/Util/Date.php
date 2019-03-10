<?php

namespace App\Util;

class Date
{
    private static $daysTwoLetters = ['Mo','Tu','We','Th','Fr','Sa','Su'];
    private static $daysThreeLetters = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

    /**
     * Convert short day name to number (1=Monday, 7=Sunday).
     * @param string $dayName
     * @return int ISO-8601 or -1 in case of unknown day name
     */
    public static function shortDayNameToNumeric(string $dayName): int
    {
        $twoCharIndex = array_search(strtolower($dayName), array_map('strtolower', self::$daysTwoLetters));
        $threeCharIndex = array_search(strtolower($dayName), array_map('strtolower', self::$daysThreeLetters));

        if (false === $twoCharIndex && false === $threeCharIndex) {
            return -1;
        }

        return max($twoCharIndex, $threeCharIndex) + 1;
    }
}