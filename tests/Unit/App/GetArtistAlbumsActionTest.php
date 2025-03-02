<?php

namespace Tests\Unit\App;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Project\App\Application\Action\GetArtistAlbumsAction;
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
use Project\Shared\Domain\Exception\InvalidArgumentException;
use Project\Shared\Domain\Exception\RateLimitExceededException;

class GetArtistAlbumsActionTest extends TestCase
{
    private SpotifyRepository $spotifyRepository;
    private TokenInformationService $tokenInformationService;
    private GetArtistAlbumsAction $getArtistAlbumsAction;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->spotifyRepository = $this->createMock(SpotifyRepository::class);
        $this->tokenInformationService = $this->createMock(TokenInformationService::class);
        $this->getArtistAlbumsAction = new GetArtistAlbumsAction($this->spotifyRepository, $this->tokenInformationService);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     * @throws InvalidArgumentException
     */
    public function testExecuteSuccess()
    {
        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $groups = new Groups('album');
        $market = new Market('ES');
        $limit = new Limit(10);
        $offset = new Offset(0);
        $spotifyToken = 'spotify_token';
        $albumsData = ['albums' => 'data'];

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtistAlbums')->willReturn($albumsData);

        $result = $this->getArtistAlbumsAction->execute($artistId, $token, $groups, $market, $limit, $offset);

        $this->assertEquals($albumsData, $result);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     * @throws InvalidArgumentException
     */
    public function testExecuteBadOrExpiredTokenException()
    {
        $this->expectException(BadOrExpiredTokenException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $groups = new Groups('album');
        $market = new Market('ES');
        $limit = new Limit(10);
        $offset = new Offset(0);

        $this->tokenInformationService->method('getSpotifyToken')->willThrowException(new BadOrExpiredTokenException());

        $this->getArtistAlbumsAction->execute($artistId, $token, $groups, $market, $limit, $offset);
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     * @throws InvalidArgumentException
     */
    public function testExecuteArtistNotFoundException()
    {
        $this->expectException(ArtistNotFoundException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $groups = new Groups('album');
        $market = new Market('ES');
        $limit = new Limit(10);
        $offset = new Offset(0);
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtistAlbums')->willThrowException(new ArtistNotFoundException());

        $this->getArtistAlbumsAction->execute($artistId, $token, $groups, $market, $limit, $offset);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     * @throws InvalidArgumentException
     */
    public function testExecuteBadOAuthRequestException()
    {
        $this->expectException(BadOAuthRequestException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $groups = new Groups('album');
        $market = new Market('ES');
        $limit = new Limit(10);
        $offset = new Offset(0);
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtistAlbums')->willThrowException(new BadOAuthRequestException());

        $this->getArtistAlbumsAction->execute($artistId, $token, $groups, $market, $limit, $offset);
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws FailedSpotifyConnection
     * @throws InvalidArgumentException
     */
    public function testExecuteRateLimitExceededException()
    {
        $this->expectException(RateLimitExceededException::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $groups = new Groups('album');
        $market = new Market('ES');
        $limit = new Limit(10);
        $offset = new Offset(0);
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtistAlbums')->willThrowException(new RateLimitExceededException());

        $this->getArtistAlbumsAction->execute($artistId, $token, $groups, $market, $limit, $offset);
    }

    /**
     * @throws BadOrExpiredTokenException
     * @throws ArtistNotFoundException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws InvalidArgumentException
     */
    public function testExecuteFailedSpotifyConnection()
    {
        $this->expectException(FailedSpotifyConnection::class);

        $artistId = new ArtistId('artist_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $groups = new Groups('album');
        $market = new Market('ES');
        $limit = new Limit(10);
        $offset = new Offset(0);
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getArtistAlbums')->willThrowException(new FailedSpotifyConnection());

        $this->getArtistAlbumsAction->execute($artistId, $token, $groups, $market, $limit, $offset);
    }
}
