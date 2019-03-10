<?php

namespace App\Command\RecordConverter;


use App\Entity\OpeningInterval;
use App\Entity\Restaurant;
use App\Util\DateTimeExpression;

class DataTypeB extends AbstractConverter
{
    protected $length = 2;

    public function createRestaurant(array $data): Restaurant
    {
        $this->checkLength($data);

        $restaurant = new Restaurant();

        foreach ($this->readIntervals($data[1]) as $interval) {
            $restaurant->addOpeningInterval($interval);
        }

        return $restaurant->setTitle($data[0]);
    }

    private function readIntervals(string $intervalExpression)
    {
        $dates = DateTimeExpression::parseExpression($intervalExpression);

        foreach ($dates as $date) {
            $interval = new OpeningInterval();

            yield $interval->setDayInWeek($date->dayNumber)
                    ->setOpenAt($date->start)
                    ->setCloseAt($date->end);
        }
    }
}