<?php

namespace App\Util\Struc;


class DateTime
{
    /** @var int */
    public $dayNumber;

    /** @var \DateTime */
    public $start;

    /** @var \DateTime */
    public $end;

    public function __construct(int $datNumber, \DateTime $start, \DateTime $end)
    {
        $this->dayNumber = $datNumber;
        $this->start = $start;
        $this->end = $end;
    }
}