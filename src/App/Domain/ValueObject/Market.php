<?php

namespace Project\App\Domain\ValueObject;

class Market
{
    private string $market;

    public function __construct(string $market)
    {
        $this->market = $market;
    }

    public function toString(): string
    {
        return $this->market;
    }
}
