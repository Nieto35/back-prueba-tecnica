<?php

namespace App\Http\App\Controllers;


use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\App\Application\Action\GetArtistAction;
use Project\App\Domain\ValueObject\ArtistId;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;


#[Group('Artist')]
class GetArtistController
{
    /**
     * Get artist.
     *
     * Retrieves the information of an artist by their ID.
     */
    #[PathParameter('id', required: true, type: 'string', example: "7GQDI5Vmxs92RsIRZzYT11")]
    public function __invoke(string $id, Request $request, GetArtistAction $action): JsonResponse
    {
        try {
            $artistId = new ArtistId($id);
            $authorizationHeader = $request->header('Authorization');
            $token = new Token(str_replace('Bearer ', '', $authorizationHeader));
            $data = $action->execute($artistId, $token);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (ArtistNotFoundException $e) {
            return response()->json('Artist not found ', 400);
        } catch (BadOAuthRequestException $e) {
            return response()->json('Bad OAuth request (wrong consumer key, bad nonce, expired timestamp...) ', 403);
        } catch (BadOrExpiredTokenException $e) {
            return response()->json('Bad or expired token: Please log in again.', 401);
        } catch (FailedSpotifyConnection $e) {
            return response()->json('Error connecting to Spotify server ' , 500);
        } catch (RateLimitExceededException $e) {
            return response()->json('The app has exceeded its rate limits. ', 429);
        }
        return response()->json($data);
    }
}
