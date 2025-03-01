<?php

namespace App\Http\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\Auth\Application\Action\LogInAction;
use Project\Auth\Domain\Exception\FailedCacheException;
use Project\Auth\Domain\Exception\FailedLogInException;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Password;
use Dedoc\Scramble\Attributes\QueryParameter;
use Project\Shared\Domain\Exception\FailedSpotifyConnection;

class LogInController
{

    #[QueryParameter('email', required: true, type: 'string', format: 'email', example: "user@example.com")]
    #[QueryParameter('password', required: true, type: 'string', example: "password123")]
    /**
     * @unauthenticated
     */
    public function __invoke(Request $request, LogInAction $action): JsonResponse
    {
        try {
            $email = new Email($request->input('email'));
            $password = new Password($request->input('password'));
            $token = $action->execute($email, $password);
        }catch (FailedLogInException $e) {
            return response()->json(['message' =>  $e->getMessage()], 401);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (FailedCacheException $e) {
            return response()->json('Error storing in cache in server', 500);
        } catch (FailedSpotifyConnection $e) {
            return response()->json('Error connecting to Spotify server', 500);
        }

        return response()->json(['token' => $token->toString()]);
    }
}
