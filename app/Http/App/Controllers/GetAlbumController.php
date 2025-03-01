<?php

namespace App\Http\App\Controllers;


use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\App\Application\Action\GetAlbumAction;
use Project\App\Application\Action\GetAudiobookAction;
use Project\App\Domain\ValueObject\AlbumId;
use Project\App\Domain\ValueObject\AudioBookId;
use Project\App\Domain\ValueObject\Market;
use Project\Shared\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;


#[Group('Album')]
class GetAlbumController
{
    /**
     * Get Album
     *
     *
     *  IMPORTANT: A token obtained from the auth login is required.
     */
    #[PathParameter('id', required: true, type: 'string', example: "4aawyAB9vmqN3uQ7FjRGTy")]
    #[QueryParameter('market', type: 'string',default: "ES" ,example: "ES")]
    public function __invoke(string $id, Request $request, GetAlbumAction $action): JsonResponse
    {
        try {
            $market = new Market($request->query('market'));
            $albumId= new AlbumId($id);
            $authorizationHeader = $request->header('Authorization');
            $token = new Token(str_replace('Bearer ', '', $authorizationHeader));
            $data = $action->execute($albumId, $token, $market);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (ArtistNotFoundException $e) {
            return response()->json('AudioBook not found ', 400);
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
