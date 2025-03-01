<?php

namespace Project\App\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class ArtistId
{
    private string $artistId;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($artistId)
    {
        if (!is_string($artistId)) {
            throw new InvalidArgumentException('ArtistId must be a string.');
        }
        $this->artistId = $artistId;
    }

    public function toString(): string
    {
        return $this->artistId;
    }
}
