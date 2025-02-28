<?php

namespace Project\Auth\Domain\ValueObject;

class Password
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function toString(): string
    {
        return $this->password;
    }
}
