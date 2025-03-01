<?php

namespace Project\App\Domain\Repository;


use Project\App\Domain\ValueObject\AlbumId;
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

interface SpotifyRepository
{
    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function getArtist(ArtistId $artistId,  string $spotifyToken): array;
    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function getArtistAlbums(ArtistId $artistId, string $spotifyToken, Groups $groups, Market $market, Limit $limit, Offset $offset): array;

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function getAudioBook(AudioBookId $audioBookId, string $spotifyToken, Market $market): array;

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function getAlbum(AlbumId $albumId, string $spotifyToken, Market $market): array;
}
