<?php

namespace App\Restaurant;

class RestaurantDate
{
    /** @var int */
    private $dayInWeek;

    /** @var string */
    private $time;

    public function __construct($date = null)
    {
        $dateTime = new \DateTime($date);

        $this->dayInWeek = $dateTime->format('N');
        $this->time = $dateTime->format('H:i');
    }

    public function getDayInWeek(): int
    {
        return $this->dayInWeek;
    }

    public function getTime(): string
    {
        return $this->time;
    }
}