<?php

namespace Project\App\Application\Action;


use Project\App\Domain\Repository\SpotifyRepository;
use Project\App\Domain\Service\TokenInformationService;
use Project\App\Domain\ValueObject\AlbumId;
use Project\App\Domain\ValueObject\Market;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;

class GetAlbumAction
{

    private SpotifyRepository $spotifyRepository;
    private TokenInformationService $tokenInformationService;
    public function __construct(SpotifyRepository $spotifyRepository, TokenInformationService $tokenInformationService)
    {
        $this->spotifyRepository = $spotifyRepository;
        $this->tokenInformationService = $tokenInformationService;
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function execute(AlbumId $albumId, Token $token, Market $market): array
    {
        $spotifyToken = $this->tokenInformationService->getSpotifyToken($token);
        return $this->spotifyRepository->getAlbum($albumId, $spotifyToken, $market);
    }


}
