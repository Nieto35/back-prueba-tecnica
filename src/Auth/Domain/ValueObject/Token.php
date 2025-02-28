<?php

namespace Project\Auth\Domain\ValueObject;

use Project\Auth\Domain\Exception\InvalidArgumentException;

class Token
{
    private string $token;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $token)
    {
        if (!preg_match('/^\{?[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}\}?$/', $token)) {
            throw new InvalidArgumentException("Invalid UUID format: $token");
        }
        $this->token = $token;
    }

    public function toString(): string
    {
        return $this->token;
    }
}
