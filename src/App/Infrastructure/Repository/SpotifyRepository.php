<?php

namespace Project\App\Infrastructure\Repository;
use Project\App\Domain\Repository\SpotifyRepository as SpotifyRepositoryInterface;
use Project\App\Domain\ValueObject\ArtistId;
use Project\Shared\Domain\SpotifyHttp\HttpApiSpotify;


class SpotifyRepository implements SpotifyRepositoryInterface
{
    private HttpApiSpotify $httpApiSpotify;

    public function __construct(HttpApiSpotify $httpApiSpotify)
    {
        $this->httpApiSpotify = $httpApiSpotify;
    }
    public function getArtist(ArtistId $artistId, string $spotifyToken): array
    {
        $url = 'https://api.spotify.com/v1/artists/' . $artistId->toString();

        return $this->httpApiSpotify->get($url, $spotifyToken);

    }

}
