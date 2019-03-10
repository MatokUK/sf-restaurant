<?php
namespace App\Restaurant;

use App\Entity\Restaurant;

class Availability
{
    /** @var \DateTime */
    private $date;

    public function __construct($date = null)
    {
        $this->date = new \DateTime($date);
    }

    public function isOpen(Restaurant $restaurant): bool
    {
        foreach ($restaurant->getOpeningIntervals() as $openingInterval) {
            if ($this->sameDay($openingInterval) && $this->openAtTime($openingInterval)) {
                return true;
            }
        }

        return false;
    }

    private function sameDay($openingInterval): bool
    {
        return $this->date->format('N') == $openingInterval->getDayInWeek();
    }

    private function openAtTime($openingInterval): bool
    {
        $currentTime = (int) $this->date->format('Gi');
        $start = (int) $openingInterval->getOpenAt()->format('Hi');
        $end = (int) $openingInterval->getCloseAt()->format('Hi');

        return $start <= $currentTime && $currentTime < $end;
    }
}