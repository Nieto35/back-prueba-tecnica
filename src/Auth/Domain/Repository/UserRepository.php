<?php

namespace Project\Auth\Domain\Repository;

use Project\Auth\Domain\Aggregate\User;
use Project\Auth\Domain\Exception\FailedToCreateException;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Email;

interface UserRepository
{
    /**
     * @throws FailedToCreateException
     */
    public function create(User $user): void;

    /**
     * @throws InvalidArgumentException
     */
    public function findByEmail(Email $email): ?User;
}
