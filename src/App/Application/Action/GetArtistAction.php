<?php

namespace Project\App\Application\Action;


use Illuminate\Support\Facades\Cache;
use Project\App\Domain\Repository\SpotifyRepository;
use Project\App\Domain\ValueObject\ArtistId;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;

class GetArtistAction
{

    private SpotifyRepository $spotifyRepository;
    public function __construct(SpotifyRepository $spotifyRepository)
    {
        $this->spotifyRepository = $spotifyRepository;
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function execute(ArtistId $artistId, Token $token): array
    {
        $cachedData = Cache::get("auth_token:{$token->toString()}");
        $spotifyToken = $cachedData['spotify_token'];
        return $this->spotifyRepository->getArtist($artistId, $spotifyToken);
    }


}
