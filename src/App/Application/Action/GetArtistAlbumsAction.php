<?php

namespace Project\App\Application\Action;


use Project\App\Domain\Repository\SpotifyRepository;
use Project\App\Domain\Service\TokenInformationService;
use Project\App\Domain\ValueObject\ArtistId;
use Project\App\Domain\ValueObject\Groups;
use Project\App\Domain\ValueObject\Limit;
use Project\App\Domain\ValueObject\Market;
use Project\App\Domain\ValueObject\Offset;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;

class GetArtistAlbumsAction
{

    private SpotifyRepository $spotifyRepository;
    private TokenInformationService $tokenInformationService;
    public function __construct(SpotifyRepository $spotifyRepository, TokenInformationService $tokenInformationService)
    {
        $this->spotifyRepository = $spotifyRepository;
        $this->tokenInformationService = $tokenInformationService;
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function execute(
        ArtistId $artistId,
        Token $token,
        ?Groups $groups,
        ?Market $market,
        ?Limit $limit,
        ?Offset $offset
    ): array {
        $spotifyToken = $this->tokenInformationService->getSpotifyToken($token);
        return $this->spotifyRepository->getArtistAlbums($artistId, $spotifyToken, $groups?->toString(), $market?->toString(), $limit?->toInt(), $offset?->toInt());
    }


}
