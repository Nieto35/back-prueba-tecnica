<?php

namespace Project\Shared\Domain\SpotifyHttp;


use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;

interface HttpApiSpotify
{
    /**
     * @throws FailedSpotifyConnection
     */
    public function getToken(): string;

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function get(string $url, string $spotifyToken, array $params = []): array;

}
