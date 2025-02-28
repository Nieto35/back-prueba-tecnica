<?php

namespace App\Http\Auth\Controllers;

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

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, SignInAction $action): JsonResponse
    {

        $email = new Email($request->input('email'));
        $password = new Password($request->input('password'));
        $name = new Name($request->input('name'));

        try {
            $action->execute($email, $name, $password);
        } catch (FailedToCreateException $e) {
            return response()->json(['message' => 'Failed to create user'], 500);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => 'Invalid argument'], 400);
        } catch (UserExistException $e) {
            return response()->json(['message' => 'User already exists'], 400);
        }

        return response()->json();
    }
}
