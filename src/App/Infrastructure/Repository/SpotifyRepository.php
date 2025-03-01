<?php

namespace Project\App\Infrastructure\Repository;
use Project\App\Domain\Repository\SpotifyRepository as SpotifyRepositoryInterface;
use Project\App\Domain\ValueObject\ArtistId;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;
use Project\Shared\Domain\SpotifyHttp\HttpApiSpotify;


class SpotifyRepository implements SpotifyRepositoryInterface
{
    private HttpApiSpotify $httpApiSpotify;

    public function __construct(HttpApiSpotify $httpApiSpotify)
    {
        $this->httpApiSpotify = $httpApiSpotify;
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function getArtist(ArtistId $artistId, string $spotifyToken): array
    {
        $url = 'https://api.spotify.com/v1/artists/' . $artistId->toString();

        return $this->httpApiSpotify->get($url, $spotifyToken);

    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function getArtistAlbums(ArtistId $artistId, string $spotifyToken, ?string $groups, ?string $market, ?int $limit, ?int $offset): array
    {
        $url = 'https://api.spotify.com/v1/artists/' . $artistId->toString() . '/albums';
        $params = [];
        if ($groups) {
            $params['groups'] = $groups;
        }
        if ($market) {
            $params['market'] = $market;
        }
        if ($limit) {
            $params['limit'] = $limit;
        }
        if ($offset) {
            $params['offset'] = $offset;
        }

        return $this->httpApiSpotify->get($url, $spotifyToken, $params);
    }

}
