<?php

namespace App\Http\Auth\Controllers;

use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\Auth\Application\Action\SignInAction;
use Project\Auth\Domain\Exception\FailedToCreateException;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\Exception\UserExistException;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;
use Project\Auth\Domain\ValueObject\Password;

class SignInController
{
    #[QueryParameter('email', required: true, type: 'string', format: 'email', example: "user@example.com")]
    #[QueryParameter('password', required: true, type: 'string', example: "password123")]
    #[QueryParameter('name', required: true, type: 'string', example: "Pepito Perez")]
    public function __invoke(Request $request, SignInAction $action): JsonResponse
    {
        try {
            $email = new Email($request->input('email'));
            $password = new Password($request->input('password'));
            $name = new Name($request->input('name'));
            $action->execute($email, $name, $password);
        }catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (UserExistException $e) {
            return response()->json(['message' => 'User already exists'], 409);
        } catch (FailedToCreateException $e) {
            return response()->json(['message' => 'Failed to create user due to a database error.'], 500);
        }

        return response()->json();
    }
}
