<?php

namespace Tests\Unit\App;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Project\App\Application\Action\GetArtistAction;
use Project\App\Domain\Repository\SpotifyRepository;
use Project\App\Domain\Service\TokenInformationService;
use Project\App\Domain\ValueObject\ArtistId;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;

class GetArtistActionTest extends TestCase
{
    private SpotifyRepository $spotifyRepository;
    private TokenInformationService $tokenInformationService;
    private GetArtistAction $getArtistAction;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->spotifyRepository = $this->createMock(SpotifyRepository::class);
        $this->tokenInformationService = $this->createMock(TokenInformationService::class);
        $this->getArtistAction = new GetArtistAction($this->spotifyRepository, $this->tokenInformationService);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function testExecuteSuccess()
    {
        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $spotifyToken = 'spotify_token';
        $artistData = ['artist' => 'data'];

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtist')->willReturn($artistData);

        $result = $this->getArtistAction->execute($artistId, $token);

        $this->assertEquals($artistData, $result);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function testExecuteBadOrExpiredTokenException()
    {
        $this->expectException(BadOrExpiredTokenException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');

        $this->tokenInformationService->method('getSpotifyToken')->willThrowException(new BadOrExpiredTokenException());

        $this->getArtistAction->execute($artistId, $token);
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function testExecuteArtistNotFoundException()
    {
        $this->expectException(ArtistNotFoundException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtist')->willThrowException(new ArtistNotFoundException());

        $this->getArtistAction->execute($artistId, $token);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function testExecuteBadOAuthRequestException()
    {
        $this->expectException(BadOAuthRequestException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtist')->willThrowException(new BadOAuthRequestException());

        $this->getArtistAction->execute($artistId, $token);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws FailedSpotifyConnection
     */
    public function testExecuteRateLimitExceededException()
    {
        $this->expectException(RateLimitExceededException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtist')->willThrowException(new RateLimitExceededException());

        $this->getArtistAction->execute($artistId, $token);
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     */
    public function testExecuteFailedSpotifyConnection()
    {
        $this->expectException(FailedSpotifyConnection::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtist')->willThrowException(new FailedSpotifyConnection());

        $this->getArtistAction->execute($artistId, $token);
    }
}
