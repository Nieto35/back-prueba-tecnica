<?php

namespace Project\Auth\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class Password
{
    private string $password;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($password)
    {
        if (!is_string($password)) {
            throw new InvalidArgumentException("Password must be a string.");
        }
        $this->password = $password;
    }

    public function toString(): string
    {
        return $this->password;
    }
}
