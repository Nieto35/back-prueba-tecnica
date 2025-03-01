<?php

namespace App\Http\App\Controllers;


use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\App\Application\Action\GetAudiobookAction;
use Project\App\Domain\ValueObject\AudioBookId;
use Project\App\Domain\ValueObject\Market;
use Project\Shared\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Token;
use Project\Shared\Domain\Exception\ArtistNotFoundException;
use Project\Shared\Domain\Exception\BadOAuthRequestException;
use Project\Shared\Domain\Exception\BadOrExpiredTokenException;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;
use Project\Shared\Domain\Exception\RateLimitExceededException;


#[Group('AudioBook')]
class GetAudioBookController
{
    /**
     * Get AudioBook (SPOTIFY TIENE ERROR EN ESTE ENDPOINT =C).
     *
     * La API COMPLETA DE AUDIO BOOKS FALLA
     * Hacer prueba, ninguna prueba funciona ni en la pagina de spotify en audio libros, siempre da error.
     * adjunto enlace de documentacion:
     * https://developer.spotify.com/documentation/web-api/reference/get-an-audiobook
     * =C
     * capturo error de servidor claramente!
     * envio captura de pantalla adjunta a correo.
     *
     *  IMPORTANT: A token obtained from the auth login is required.
     */
    #[PathParameter('id', required: true, type: 'string', example: "7iHfbu1YPACw6oZPAFJtqe")]
    #[QueryParameter('market', type: 'string',default: "ES" ,example: "ES")]
    public function __invoke(string $id, Request $request, GetAudiobookAction $action): JsonResponse
    {
        try {
            $market = new Market($request->query('market'));
            $audioBookId= new AudioBookId($id);
            $authorizationHeader = $request->header('Authorization');
            $token = new Token(str_replace('Bearer ', '', $authorizationHeader));
            $data = $action->execute($audioBookId, $token, $market);
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
