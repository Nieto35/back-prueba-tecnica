<?php

namespace App\Http\Auth\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\Auth\Application\Action\SignInAction;
use Project\Auth\Domain\Exception\FailedToCreateException;
use Project\Auth\Domain\ValueObject\UserId;
use Project\Shared\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\Exception\UserExistException;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;
use Project\Auth\Domain\ValueObject\Password;
use Ramsey\Uuid\Uuid;

#[Group('Auth')]
class SignInController
{
    /**
     * Sign in.
     *
     * Necessary for using the application, the user must register and have a user in the database to make any subsequent requests.
     *
     * @unauthenticated
     */
    #[QueryParameter('email', required: true, type: 'string', format: 'email', example: "user@example.com")]
    #[QueryParameter('password', required: true, type: 'string', example: "password123")]
    #[QueryParameter('name', required: true, type: 'string', example: "Pepito Perez")]
    public function __invoke(Request $request, SignInAction $action): JsonResponse
    {
        try {
            $email = new Email($request->input('email'));
            $password = new Password($request->input('password'));
            $name = new Name($request->input('name'));
            $id = new UserId(Uuid::uuid4()->toString());
            $action->execute($id, $email, $name, $password);
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
