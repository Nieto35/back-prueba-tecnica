<?php

namespace Project\App\Domain\ValueObject;

class Offset
{
    private int $offset;

    public function __construct(int $offset)
    {
        $this->offset = $offset;
    }

    public function toInt(): int
    {
        return $this->offset;
    }
}
