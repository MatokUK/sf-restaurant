<?php

namespace App\Tests\Restaurant;

use App\Restaurant\RestaurantDate;
use PHPUnit\Framework\TestCase;

class RestaurantDateTest extends TestCase
{
    /**
     * @param string $date
     * @param int    $expectedDayInWeek
     * @param string $expectedTime
     * @dataProvider getDates
     */
    public function testConstructor(string $date, int $expectedDayInWeek, string $expectedTime)
    {
        $restaurantDate = new RestaurantDate($date);

        $this->assertEquals($expectedDayInWeek, $restaurantDate->getDayInWeek());
        $this->assertEquals($expectedTime, $restaurantDate->getTime());
    }

    public function getDates()
    {
        return [
            ['2019-03-11 10:00', 1, '10:00'],
            ['2019-03-12 10:00:43', 2, '10:00'],
            ['2019-03-13 6:00:43', 3, '06:00'],
            ['2019-03-14 0:00:43', 4, '00:00'],
            ['2019-03-17 23:07:43', 7, '23:07'],
        ];
    }
}