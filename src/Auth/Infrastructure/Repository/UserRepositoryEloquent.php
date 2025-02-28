<?php

namespace Project\Auth\Infrastructure\Repository;

use Project\Auth\Domain\Aggregate\User;
use Project\Auth\Domain\Exception\FailedToCreateException;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\Repository\UserRepository;
use App\Models\User as UserEloquent;
use Exception;
use Project\Auth\Domain\ValueObject\Email;
class UserRepositoryEloquent implements UserRepository
{
    /**
     * @throws FailedToCreateException
     */
    public function create(User $user): void
    {
        try {
            UserEloquent::create([
                'id' => $user->getId()->toString(),
                'name' => $user->getName()->toString(),
                'email' => $user->getEmail()->toString(),
                'password' => $user->getPassword()->toString(),
            ]);
        } catch (Exception $e) {
            throw new FailedToCreateException("Eloquent failed to create user: {$e->getMessage()}");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function findByEmail(Email $email): ?User
    {
        $user = UserEloquent::where('email', $email->toString())->first();

        return $user ? User::fromEloquentModel($user) : null;
    }
}
