<?php

namespace Project\Auth\Domain\ValueObject;

class Date
{
    private ?\DateTime $date;

    public function __construct(?\DateTime $date)
    {
        $this->date = $date;
    }

    public function toDateTime(): ?\DateTime
    {
        return $this->date;
    }
}
