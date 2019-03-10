<?php

namespace App\Util;


class DateInterval
{
    /**
     * Parse expressions like:
     * Mon-Fri
     * Mon-Fri,Sun
     */
    public static function parseDayExpression(string $expression): array
    {
        $interval = [];

        $parts = preg_split('/,\s*/', $expression);
        foreach ($parts as $part) {
            $interval = array_merge($interval, static::parsePartialExpression($part));
        }

        return $interval;
    }

    private static function parsePartialExpression(string $expression)
    {
        $interval = [];

        $days = explode('-', $expression);
        foreach ($days as $key => $day) {
            $days[$key] = Date::shortDayNameToNumeric($day);
        }

        if (array_search(-1, $days) !== false) {
            throw new \InvalidArgumentException();
        }

        if (count($days) == 1) {
            return $days;
        }

        for ($i = $days[0]; $i <= $days[1]; $i++) {
            $interval[] = $i;
        }

        if (empty($interval)) {
            throw new \InvalidArgumentException();
        }

        return $interval;
    }

    public static function nextDat(int $day): int
    {
        $nextDay = ++$day % 8;

        return $nextDay == 0 ? 1 : $nextDay;
    }
}