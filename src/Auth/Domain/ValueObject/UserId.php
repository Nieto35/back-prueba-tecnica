<?php

namespace Project\Auth\Domain\ValueObject;

use Project\Auth\Domain\Exception\InvalidArgumentException;

class UserId
{
    private string $id;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $id)
    {
        if (!preg_match('/^\{?[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}\}?$/', $id)) {
            throw new InvalidArgumentException("Invalid UUID format: $id");
        }
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id;
    }
}
