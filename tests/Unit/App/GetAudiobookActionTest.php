<?php

namespace Tests\Unit\App;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Project\App\Application\Action\GetAudiobookAction;
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

class GetAudiobookActionTest extends TestCase
{
    private SpotifyRepository $spotifyRepository;
    private TokenInformationService $tokenInformationService;
    private GetAudiobookAction $getAudiobookAction;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->spotifyRepository = $this->createMock(SpotifyRepository::class);
        $this->tokenInformationService = $this->createMock(TokenInformationService::class);
        $this->getAudiobookAction = new GetAudiobookAction($this->spotifyRepository, $this->tokenInformationService);
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
        $audioBookId = new AudioBookId('audiobook_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';
        $audiobookData = ['audiobook' => 'data'];

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAudioBook')->willReturn($audiobookData);

        $result = $this->getAudiobookAction->execute($audioBookId, $token, $market);

        $this->assertEquals($audiobookData, $result);
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

        $audioBookId = new AudioBookId('audiobook_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');

        $this->tokenInformationService->method('getSpotifyToken')->willThrowException(new BadOrExpiredTokenException());

        $this->getAudiobookAction->execute($audioBookId, $token, $market);
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

        $audioBookId = new AudioBookId('audiobook_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAudioBook')->willThrowException(new ArtistNotFoundException());

        $this->getAudiobookAction->execute($audioBookId, $token, $market);
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

        $audioBookId = new AudioBookId('audiobook_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAudioBook')->willThrowException(new BadOAuthRequestException());

        $this->getAudiobookAction->execute($audioBookId, $token, $market);
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

        $audioBookId = new AudioBookId('audiobook_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAudioBook')->willThrowException(new RateLimitExceededException());

        $this->getAudiobookAction->execute($audioBookId, $token, $market);
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

        $audioBookId = new AudioBookId('audiobook_id');
        $token = new Token('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $market = new Market('ES');
        $spotifyToken = 'spotify_token';

        $this->tokenInformationService->method('getSpotifyToken')->willReturn($spotifyToken);
        $this->spotifyRepository->method('getAudioBook')->willThrowException(new FailedSpotifyConnection());

        $this->getAudiobookAction->execute($audioBookId, $token, $market);
    }
}
