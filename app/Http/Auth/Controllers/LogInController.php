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
class LogInController
{

    #[QueryParameter('email', required: true, type: 'string', format: 'email', example: "user@example.com")]
    #[QueryParameter('password', required: true, type: 'string', example: "password123")]
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
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['token' => $token->toString()]);
    }
}
