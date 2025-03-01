<?php

namespace Project\App\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class Groups
{
    private ?string $group;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($group)
    {
        if (!is_null($group) && !is_string($group)) {
            throw new InvalidArgumentException('Group must be a string or null.');
        }
        $this->group = $group;
    }

    public function toString(): ?string
    {
        return $this->group;
    }
}
