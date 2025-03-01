<?php

namespace Project\Auth\Application\Action;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Project\Auth\Domain\Exception\FailedCacheException;
use Project\Auth\Domain\Exception\FailedLogInException;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\Repository\UserRepository;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Password;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\SpotifyHttp\HttpApiSpotify;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
class LogInAction
{
    private UserRepository $userRepository;
    private HttpApiSpotify $httpApiSpotify;

    public function __construct(UserRepository $userRepository, HttpApiSpotify $httpApiSpotify)
    {
        $this->userRepository = $userRepository;
        $this->httpApiSpotify = $httpApiSpotify;
    }

    /**
     * @throws InvalidArgumentException
     * @throws FailedLogInException
     * @throws FailedCacheException
     */
    public function execute(Email $email,Password $password): Token
    {
        $user = $this->userRepository->findByEmail($email);
        if(!$user){
            throw new FailedLogInException("Incorrect username or password");
        }
        if (!Hash::check($password->toString(), $user->getPassword()->toString())) {
            throw new FailedLogInException("Incorrect username or password");
        }

        $token = new Token(Uuid::uuid4()->toString());

        $spotifyToken = $this->httpApiSpotify->getToken();

        Cache::put("auth_token:{$token->toString()}", [
            'user_id' => $user->getId()->toString(),
            'name' => $user->getName()->toString(),
            'email' => $user->getEmail()->toString(),
            'spotify_token' => $spotifyToken
        ]);



        $cachedData = Cache::get("auth_token:{$token->toString()}");
        if (!$cachedData) {
            throw new FailedCacheException("Failed to store token in cache");
        }
        return $token;

    }


}
