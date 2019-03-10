<?php

namespace App\Command\RecordConverter;


use App\Entity\Restaurant;

abstract class AbstractConverter
{
    protected $length = 1;

    protected function checkLength(array $data)
    {
        if (!isset($data[$this->length - 1])) {
            throw new \RangeException(sprintf("Expected array of length %d, but given array has length %d", $this->length, count($data)));
        }
    }

    abstract public function createRestaurant(array $data): Restaurant;
}