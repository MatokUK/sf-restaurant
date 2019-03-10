<?php

namespace App\Util;


use App\Util\Struc\DateTime;

class DateTimeExpression
{
    /**
     * @param string $expression
     * @return DateTime[]|array
     */
    public static function parseExpression(string $expression): array
    {
        $parts = preg_split('#\s*/\s*#', $expression);

        $intervals = [];

        foreach ($parts as $part) {
            [$dayPart, $start, $end] = static::splitDayAndTime($part);
            $dayNumbers = DateInterval::parseDayExpression($dayPart);
            $intervals = array_merge($intervals, static::getDateTimeEntries($dayNumbers, $start, $end));
        }

        return $intervals;
    }

    private static function splitDayAndTime(string $expression):array
    {
        preg_match('/^([A-Za-z, -]+)\s([^-]+?)\s*-\s*(.+)$/', $expression, $matches);

        return array($matches[1], $matches[2], $matches[3]);
    }

    private static function getDateTimeEntries(array $dayNumbers, string $start, string $end): array
    {
        $entries = [];
        foreach ($dayNumbers as $dayNumber) {
            $entries = array_merge($entries, static::getDateTimeEntryForDay($dayNumber, $start, $end));
        }

        return $entries;
    }

    private static function getDateTimeEntryForDay(int $dayNumber, string $start, string $end): array
    {
        $startDateTime = new \DateTime($start);
        $endDateTime = new \DateTime($end);

        if ($startDateTime < $endDateTime) {
            return [new DateTime($dayNumber, $startDateTime, $endDateTime)];
        }

        return [
            new DateTime($dayNumber, $startDateTime, new \DateTime('23:59:59')),
            new DateTime(DateInterval::nextDat($dayNumber), new \DateTime('00:00:00'), $endDateTime),
        ];
    }
}