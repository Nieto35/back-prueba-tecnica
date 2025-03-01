<?php

namespace Project\App\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class AudioBookId
{
    private string $audioBookId;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($audioBookId)
    {
        if (!is_string($audioBookId)) {
            throw new InvalidArgumentException('AudioBookId must be a string.');
        }
        $this->audioBookId = $audioBookId;
    }

    public function toString(): string
    {
        return $this->audioBookId;
    }
}
