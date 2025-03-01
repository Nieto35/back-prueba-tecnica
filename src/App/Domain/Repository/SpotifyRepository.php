<?php

namespace Project\App\Domain\Repository;


use Project\App\Domain\ValueObject\ArtistId;

interface SpotifyRepository
{
    public function getArtist(ArtistId $artistId,  string $spotifyToken): array;

}
