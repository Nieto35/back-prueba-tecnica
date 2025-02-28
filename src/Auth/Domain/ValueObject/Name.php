<?php

namespace Project\Auth\Domain\ValueObject;

class Name
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
