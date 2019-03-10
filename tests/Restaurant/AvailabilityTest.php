<?php
namespace App\Tests\Restaurant;


use App\Entity\OpeningInterval;
use App\Entity\Restaurant;
use App\Restaurant\Availability;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class AvailabilityTest extends TestCase
{
    public function testClosedWhenEmptyInterval()
    {
        $intervals = new ArrayCollection();
        $restaurant = $this->createMock(Restaurant::class);
        $restaurant->method('getOpeningIntervals')
            ->willReturn($intervals);

        $availability = new Availability('2019-03-11');
        $isOpen = $availability->isOpen($restaurant);

        $this->assertFalse($isOpen);
    }

    /**
     * @param string $date
     * @param bool   $expectedResult
     * @dataProvider getTimes
     */
    public function testIsOpen(string $date, bool $expectedResult)
    {
        $intervals = new ArrayCollection();
        $intervals->add($this->createInterval(1, '10:00', '13:00'));
        $intervals->add($this->createInterval(1, '16:00', '23:00'));

        $restaurant = $this->createMock(Restaurant::class);
        $restaurant->method('getOpeningIntervals')
            ->willReturn($intervals);

        $availability = new Availability($date);
        $isOpen = $availability->isOpen($restaurant);

        $this->assertEquals($isOpen, $expectedResult);
    }

    private function createInterval(int $dayInWeek, string $start, string $end): OpeningInterval
    {
        $interval = new OpeningInterval();
        $interval->setDayInWeek($dayInWeek)
            ->setOpenAt(new \DateTime($start))
            ->setCloseAt(new \DateTime($end));

        return $interval;
    }
    
    public function getTimes()
    {
        return [
            ['2019-03-11 9:55', false],
            ['2019-03-11 10:00', true],
            ['2019-03-11 11:00', true],
            ['2019-03-11 13:00', false],
            ['2019-03-11 13:10', false],
            ['2019-03-11 16:00', true],
            ['2019-03-11 22:10', true],
            ['2019-03-12 22:10', false],
            ['2019-03-18 22:10', true],
        ];
    }
}