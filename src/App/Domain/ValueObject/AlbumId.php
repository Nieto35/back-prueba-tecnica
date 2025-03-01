<?php

namespace Project\App\Domain\ValueObject;

use Project\Shared\Domain\Exception\InvalidArgumentException;

class AlbumId
{
    private string $albumId;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct($albumId)
    {
        if (!is_string($albumId)) {
            throw new InvalidArgumentException('AlbumId must be a string.');
        }
        $this->albumId = $albumId;
    }

    public function toString(): string
    {
        return $this->albumId;
    }
}
