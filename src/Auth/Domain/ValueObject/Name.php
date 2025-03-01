<?php

namespace Project\Auth\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class Name
{
    private string $name;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($name)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException("Name must be a string.");
        }
        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
