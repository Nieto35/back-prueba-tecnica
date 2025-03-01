<?php

namespace Project\App\Infrastructure\Repository;
use Project\App\Domain\Repository\SpotifyRepository as SpotifyRepositoryInterface;
use Project\App\Domain\ValueObject\ArtistId;
use Project\App\Domain\ValueObject\AudioBookId;
use Project\App\Domain\ValueObject\Groups;
use Project\App\Domain\ValueObject\Limit;
use Project\App\Domain\ValueObject\Market;
use Project\App\Domain\ValueObject\Offset;
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
    public function getArtistAlbums(ArtistId $artistId, string $spotifyToken, Groups $groups, Market $market, Limit $limit, Offset $offset): array
    {
        $url = 'https://api.spotify.com/v1/artists/' . $artistId->toString() . '/albums';
        $params = [];
        if ($groups->toString()) {
            $params['groups'] = $groups->toString();
        }
        if ($market->toString()) {
            $params['market'] = $market->toString();
        }
        if ($limit->toInt()) {
            $params['limit'] = $limit->toInt();
        }
        if ($offset->toInt()) {
            $params['offset'] = $offset->toInt();
        }

        return $this->httpApiSpotify->get($url, $spotifyToken, $params);
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function getAudioBook(AudioBookId $audioBookId, string $spotifyToken, Market $market): array
    {
        $url = 'https://api.spotify.com/v1/audiobooks/' . $audioBookId->toString();
        $params = [];
        if ($market->toString()) {
            $params['market'] = $market->toString();
        }

        return $this->httpApiSpotify->get($url, $spotifyToken, $params);
    }

}
