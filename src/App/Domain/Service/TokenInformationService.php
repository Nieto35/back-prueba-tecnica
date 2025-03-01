<?php

namespace Project\App\Domain\Service;

use Project\Auth\Domain\ValueObject\Token;
use Illuminate\Support\Facades\Cache;

class TokenInformationService
{
    public function getSpotifyToken(Token $token): string
    {
        $cachedData = Cache::get("auth_token:{$token->toString()}");
        return $cachedData['spotify_token'];
    }
}
