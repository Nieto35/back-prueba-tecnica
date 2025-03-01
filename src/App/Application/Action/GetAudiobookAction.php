<?php

namespace Project\App\Application\Action;


use Project\App\Domain\Repository\SpotifyRepository;
use Project\App\Domain\Service\TokenInformationService;
use Project\App\Domain\ValueObject\AudioBookId;
use Project\App\Domain\ValueObject\Market;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;

class GetAudiobookAction
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
    public function execute(AudioBookId $audioBookId, Token $token, Market $market): array
    {
        $spotifyToken = $this->tokenInformationService->getSpotifyToken($token);
        return $this->spotifyRepository->getAudioBook($audioBookId, $spotifyToken, $market);
    }


}
