<?php

namespace App\Tests\Util;

use App\Util\DateInterval;
use PHPUnit\Framework\TestCase;

class DateIntervalTest extends TestCase
{
    /**
     * @param string $expression
     * @param array  $expectedResult
     * @dataProvider validExpression
     */
    public function testParseDayExpression(string $expression, array $expectedResult)
    {
        $interval = DateInterval::parseDayExpression($expression);

        $this->assertEquals($expectedResult, $interval);
    }

    /**
     * @param string $expression
     * @dataProvider invalidExpression
     */
    public function testParseDayExpressionThrowExceptionOnInvalid(string $expression)
    {
        $this->expectException(\InvalidArgumentException::class);

        DateInterval::parseDayExpression($expression);
    }

    /**
     * @param int $dayNumber
     * @param int $expectedNextDayNumber
     * @dataProvider nextDay
     */
    public function testNextDay(int $dayNumber, int $expectedNextDayNumber)
    {
        $nextDay = DateInterval::nextDat($dayNumber);

        $this->assertEquals($expectedNextDayNumber, $nextDay);
    }

    public function validExpression()
    {
        return [
            ['Wed-Sun', [3, 4, 5, 6, 7]],
            ['Mon-Fri', [1, 2, 3, 4, 5]],
            ['Mon-Tue', [1, 2]],
            ['Mon-Mon', [1]],
            ['Mon', [1]],
            ['Mon,Fri', [1,5]],
            ['Mon-Thu, Sun', [1,2,3,4,7]]
        ];
    }

    public function invalidExpression()
    {
        return [
            ['Sun-Wed'],
            ['xxx'],
            ['Mon,abc']
        ];
    }

    public function nextDay()
    {
        return [
            [1, 2],
            [2, 3],
            [3, 4],
            [4, 5],
            [5, 6],
            [6, 7],
            [7, 1],
        ];
    }
}