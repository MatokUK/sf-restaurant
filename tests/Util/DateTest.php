<?php

namespace App\Tests\Util;

use App\Util\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /**
     * @param string $shortDay
     * @param int $expectedDayNumber
     * @dataProvider getShortsDays
     * @dataProvider getShortsDaysShuffledCase
     */
    public function testConvertDayName(string $shortDay, int $expectedDayNumber)
    {
        $dayNumber = Date::shortDayNameToNumeric($shortDay);

        $this->assertEquals($expectedDayNumber, $dayNumber);
    }

    public function testUnknownDay()
    {
        $dayNumber = Date::shortDayNameToNumeric('xxx');

        $this->assertEquals(-1, $dayNumber);
    }


    public function getShortsDays()
    {
        $twoLetters = [
            ['Mo', 1],
            ['Tu', 2],
            ['We', 3],
            ['Th', 4],
            ['Fr', 5],
            ['Sa', 6],
            ['Su', 7],
        ];

        $threeLetters = [
            ['Mon', 1],
            ['Tue', 2],
            ['Wed', 3],
            ['Thu', 4],
            ['Fri', 5],
            ['Sat', 6],
            ['Sun', 7],
        ];

        return array_merge($twoLetters, $threeLetters);
    }

    public function getShortsDaysShuffledCase()
    {
        $shortNames = $this->getShortsDays();

        foreach ($shortNames as $key => $shortName) {
            $shortNames[$key][0] = $this->shuffleCase($shortName[0]);
        }

        return $shortNames;
    }

    private function shuffleCase(string $name)
    {
        $length = strlen($name);
        for ($i = 0; $i < $length; $i++) {
            $name[$i] = rand(0,1) ? strtolower($name[$i]) : strtoupper($name[$i]);
        }

        return $name;
    }
}
