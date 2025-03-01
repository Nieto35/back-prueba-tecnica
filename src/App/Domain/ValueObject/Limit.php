<?php

namespace Project\App\Domain\ValueObject;

class Limit
{
    private int $limit;

    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    public function toInt(): int
    {
        return $this->limit;
    }
}
