<?php

namespace Project\App\Domain\ValueObject;

class ArtistId
{
    private string $artistId;

    public function __construct(string $artistId)
    {
        $this->artistId = $artistId;
    }

    public function toString(): string
    {
        return $this->artistId;
    }
}
