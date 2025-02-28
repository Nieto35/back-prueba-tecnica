<?php

namespace Project\Auth\Domain\ValueObject;

use Project\Auth\Domain\Exception\InvalidArgumentException;

class Email
{
    private string $email;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: $email");
        }
        $this->email = $email;
    }

    public function toString(): string
    {
        return $this->email;
    }
}
