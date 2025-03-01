<?php

namespace Project\App\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class Limit
{
    private ?int $limit;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($limit)
    {
        if (is_string($limit) && is_numeric($limit)) {
            $limit = (int) $limit;
        }
        if (!is_null($limit) && !is_int($limit)) {
            throw new InvalidArgumentException('Limit must be an integer or null.');
        }
        $this->limit = $limit;
    }

    public function toInt(): ?int
    {
        return $this->limit;
    }
}
