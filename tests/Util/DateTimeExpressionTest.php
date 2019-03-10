<?php

namespace App\Tests\Util;

use App\Util\DateTimeExpression;
use PHPUnit\Framework\TestCase;

class DateTimeExpressionTest extends TestCase
{
    /**
     * @param string $expression
     * @param int $expectedIntervalCount
     * @param string $expectedStart
     * @param string $expectedEnd
     * @dataProvider getExpression
     */
    public function testParseExpression(string $expression, int $expectedIntervalCount, string $expectedStart, string $expectedEnd)
    {
        $intervals = DateTimeExpression::parseExpression($expression);

        $this->assertCount($expectedIntervalCount, $intervals);
        $this->assertEquals($expectedStart, $intervals[0]->start->format('H:i'));
        $this->assertEquals($expectedEnd, $intervals[0]->end->format('H:i'));
    }

    public function testParseComplexExpression()
    {
        $intervals = DateTimeExpression::parseExpression('Mon-Tue, Sun 10 am - 10:30 pm  / Fri-Sat 10 am - 12:30 am');

        $startIntervalHashSet = [];
        $endIntervalHashSet = [];
        foreach ($intervals as $interval) {
            $startIntervalHashSet[$interval->start->format('H:i')] = 1;
            $endIntervalHashSet[$interval->end->format('H:i')] = 1;
        }
        $this->assertCount(7, $intervals);
        $this->assertArrayHasKey('10:00', $startIntervalHashSet);
        $this->assertArrayHasKey('00:00', $startIntervalHashSet);
        $this->assertArrayHasKey('22:30', $endIntervalHashSet);
        $this->assertArrayHasKey('00:30', $endIntervalHashSet);
        $this->assertArrayHasKey('23:59', $endIntervalHashSet);
    }

    public function getExpression()
    {
        return [
            ['Mon-Tue 11 am - 11 pm', 2, '11:00', '23:00'],
            ['Mon-Tue 5:30 pm - 2 am', 4, '17:30', '23:59'],
            ['Mon-Tue, Sun 10 am - 10:30 pm', 3, '10:00', '22:30'],
        ];
    }
}