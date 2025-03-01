<?php

namespace Project\App\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class Offset
{
    private ?int $offset;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($offset)
    {
        if (is_string($offset) && is_numeric($offset)) {
            $offset = (int) $offset;
        }
        if (!is_null($offset) && !is_int($offset)) {
            throw new InvalidArgumentException('Offset must be an integer or null.');
        }
        $this->offset = $offset;
    }

    public function toInt(): ?int
    {
        return $this->offset;
    }
}
