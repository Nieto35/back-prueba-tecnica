<?php

namespace Project\App\Application\Action;


use Illuminate\Support\Facades\Cache;
use Project\App\Domain\Repository\SpotifyRepository;
use Project\App\Domain\ValueObject\ArtistId;
use Project\Auth\Domain\ValueObject\Token;

class GetArtistAction
{

    private SpotifyRepository $spotifyRepository;
    public function __construct(SpotifyRepository $spotifyRepository)
    {
        $this->spotifyRepository = $spotifyRepository;
    }
    public function execute(ArtistId $artistId, Token $token): array
    {
        $cachedData = Cache::get("auth_token:{$token->toString()}");
        $spotifyToken = $cachedData['spotify_token'];
        return $this->spotifyRepository->getArtist($artistId, $spotifyToken);
    }


}
