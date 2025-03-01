<?php

namespace Project\App\Domain\ValueObject;

class Groups
{
    private string $group;

    public function __construct(string $group)
    {
        $this->group = $group;
    }

    public function toString(): string
    {
        return $this->group;
    }
}
