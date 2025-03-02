<?php

namespace Tests\Unit\Auth;

use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\MockObject\Exception;
use Project\Auth\Application\Action\LogInAction;
use Project\Auth\Domain\Repository\UserRepository;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;
use Project\Auth\Domain\ValueObject\Password;
use Project\Auth\Domain\ValueObject\Token;
use Project\Auth\Domain\ValueObject\UserId;
use Project\Shared\Domain\Exception\InvalidArgumentException;
use Project\Shared\Domain\SpotifyHttp\HttpApiSpotify;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Project\Auth\Domain\Exception\FailedLogInException;
use Project\Auth\Domain\Exception\FailedCacheException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Auth\Domain\Aggregate\User;
use Ramsey\Uuid\Uuid;

class LogInActionTest extends TestCase
{
    private UserRepository $userRepository;
    private HttpApiSpotify $httpApiSpotify;
    private LogInAction $logInAction;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->httpApiSpotify = $this->createMock(HttpApiSpotify::class);
        $this->logInAction = new LogInAction($this->userRepository, $this->httpApiSpotify);
    }

    /**
     * @throws FailedCacheException
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws FailedLogInException
     * @throws FailedSpotifyConnection
     */
    public function testExecuteSuccess()
    {
        $email = new Email('test@example.com');
        $password = new Password('password');
        $user = $this->createMock(User::class);
        $token = new Token(Uuid::uuid4()->toString());
        $spotifyToken = 'spotify_token';

        $this->userRepository->method('findByEmail')->willReturn($user);
        $user->method('getPassword')->willReturn(new Password(Hash::make('password')));
        $user->method('getId')->willReturn(new UserId(Uuid::uuid4()->toString()));
        $user->method('getName')->willReturn(new Name('Test User'));
        $user->method('getEmail')->willReturn(new Email('test@example.com'));

        $this->httpApiSpotify->method('getToken')->willReturn($spotifyToken);

        Cache::shouldReceive('put')->once();
        Cache::shouldReceive('get')->andReturn([
            'user_id' => $user->getId()->toString(),
            'name' => $user->getName()->toString(),
            'email' => $user->getEmail()->toString(),
            'spotify_token' => $spotifyToken
        ]);

        $result = $this->logInAction->execute($email, $password);

        $this->assertInstanceOf(Token::class, $result);
    }

    public function testExecuteFailedLogIn()
    {
        $this->expectException(FailedLogInException::class);

        $email = new Email('test@example.com');
        $password = new Password('password');

        $this->userRepository->method('findByEmail')->willReturn(null);

        $this->logInAction->execute($email, $password);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws FailedLogInException
     * @throws FailedSpotifyConnection
     */
    public function testExecuteFailedCache()
    {
        $this->expectException(FailedCacheException::class);

        $email = new Email('test@example.com');
        $password = new Password('password');
        $user = $this->createMock(User::class);

        $this->userRepository->method('findByEmail')->willReturn($user);
        $user->method('getPassword')->willReturn(new Password(Hash::make('password')));

        $this->httpApiSpotify->method('getToken')->willReturn('spotify_token');

        Cache::shouldReceive('put')->once();
        Cache::shouldReceive('get')->andReturn(null);

        $this->logInAction->execute($email, $password);
    }

    /**
     * @throws FailedCacheException
     * @throws InvalidArgumentException
     * @throws FailedLogInException
     * @throws Exception
     */
    public function testExecuteFailedSpotifyConnection()
    {
        $this->expectException(FailedSpotifyConnection::class);

        $email = new Email('test@example.com');
        $password = new Password('password');
        $user = $this->createMock(User::class);

        $this->userRepository->method('findByEmail')->willReturn($user);
        $user->method('getPassword')->willReturn(new Password(Hash::make('password')));

        $this->httpApiSpotify->method('getToken')->willThrowException(new FailedSpotifyConnection('Failed to obtain Spotify access token'));

        $this->logInAction->execute($email, $password);
    }
}
