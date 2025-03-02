<?php

namespace Tests\Unit\App;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Project\App\Application\Action\GetAlbumAction;
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

class GetAlbumActionTest extends TestCase
{
    private SpotifyRepository $spotifyRepository;
    private TokenInformationService $tokenInformationService;
    private GetAlbumAction $getAlbumAction;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->spotifyRepository = $this->createMock(SpotifyRepository::class);
        $this->tokenInformationService = $this->createMock(TokenInformationService::class);
        $this->getAlbumAction = new GetAlbumAction($this->spotifyRepository, $this->tokenInformationService);
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
        $albumId = new AlbumId('album_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';
        $albumData = ['album' => 'data'];

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAlbum')->willReturn($albumData);

        $result = $this->getAlbumAction->execute($albumId, $token, $market);

        $this->assertEquals($albumData, $result);
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

        $albumId = new AlbumId('album_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');

        $this->tokenInformationService->method('getSpotifyToken')->willThrowException(new BadOrExpiredTokenException());

        $this->getAlbumAction->execute($albumId, $token, $market);
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

        $albumId = new AlbumId('album_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAlbum')->willThrowException(new ArtistNotFoundException());

        $this->getAlbumAction->execute($albumId, $token, $market);
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

        $albumId = new AlbumId('album_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAlbum')->willThrowException(new BadOAuthRequestException());

        $this->getAlbumAction->execute($albumId, $token, $market);
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

        $albumId = new AlbumId('album_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAlbum')->willThrowException(new RateLimitExceededException());

        $this->getAlbumAction->execute($albumId, $token, $market);
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

        $albumId = new AlbumId('album_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAlbum')->willThrowException(new FailedSpotifyConnection());

        $this->getAlbumAction->execute($albumId, $token, $market);
    }
}
