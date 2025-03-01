<?php

namespace App\Http\App\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\App\Application\Action\GetArtistAlbumsAction;
use Project\App\Domain\ValueObject\ArtistId;
use Project\App\Domain\ValueObject\Groups;
use Project\App\Domain\ValueObject\Limit;
use Project\App\Domain\ValueObject\Market;
use Project\App\Domain\ValueObject\Offset;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;

#[Group('Artist')]
class GetArtistAlbumsController
{
    /**
     * Get artist albums.
     *
     * Retrieves the albums of an artist by their ID.
     * Can receive parameters to filter the albums of the artist.
     *
     * IMPORTANT: A token obtained from the auth login is required.
     */
    #[PathParameter('id', required: true, type: 'string', example: "0TnOYISbd1XYRBk9myaseg")]
    #[QueryParameter('include_groups', type: 'string', default: "single,appears_on", example: "single,appears_on")]
    #[QueryParameter('market', type: 'string',default: "ES" ,example: "ES")]
    #[QueryParameter('limit', type: 'int', default: "10", example: "10")]
    #[QueryParameter('offset', type: 'int', default: "5" ,example: "5")]
    public function __invoke(string $id, Request $request, GetArtistAlbumsAction $action): JsonResponse
    {
        try {
            $artistId = new ArtistId($id);
            $token = new Token($request->bearerToken());
            $groups = $request->query('include_groups') ? new Groups($request->query('include_groups')) : null;
            $market = $request->query('market') ? new Market($request->query('market')) : null;
            $limit = $request->query('limit') ? new Limit((int)$request->query('limit')) : null;
            $offset = $request->query('offset') ? new Offset((int)$request->query('offset')) : null;
            $data = $action->execute($artistId, $token, $groups, $market, $limit, $offset);
        }catch (InvalidArgumentException $e) {
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
