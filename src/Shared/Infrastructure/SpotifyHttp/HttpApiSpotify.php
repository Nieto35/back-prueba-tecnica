<?php

namespace Project\Shared\Infrastructure\SpotifyHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;
use Project\Shared\Domain\SpotifyHttp\HttpApiSpotify as HttpApiSpotifyInterface;


class HttpApiSpotify implements HttpApiSpotifyInterface
{

    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.spotify.client_id');
        $this->clientSecret = config('services.spotify.client_secret');
    }


    /**
     * @throws FailedSpotifyConnection
     */
    public function getToken(): string
    {
        try {
            $client = new Client();
            $response = $client->post('https://accounts.spotify.com/api/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return $body['access_token'];
        } catch (GuzzleException $e) {
            throw new FailedSpotifyConnection('Failed to obtain Spotify access token: ' . $e->getMessage());
        }
    }

    /**
     * @throws ArtistNotFoundException
     * @throws BadOrExpiredTokenException
     * @throws BadOAuthRequestException
     * @throws RateLimitExceededException
     * @throws FailedSpotifyConnection
     */
    public function get(string $url, string $spotifyToken): array
    {
        try {
            $client = new Client();
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $spotifyToken,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            if ($e->getCode() === 400) {
                throw new ArtistNotFoundException();
            }
            if ($e->getCode() === 401) {
                throw new BadOrExpiredTokenException();
            }
            if ($e->getCode() === 403) {
                throw new BadOAuthRequestException();
            }
            if ($e->getCode() === 429) {
                throw new RateLimitExceededException();
            }
            throw new FailedSpotifyConnection();
        }
    }

}
