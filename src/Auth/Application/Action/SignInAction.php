<?php

namespace Project\Auth\Application\Action;

use Project\Auth\Domain\Exception\FailedToCreateException;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\Exception\UserExistException;
use Project\Auth\Domain\Repository\UserRepository;
use Project\Auth\Domain\Aggregate\User;
use Project\Auth\Domain\ValueObject\Date;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;
use Project\Auth\Domain\ValueObject\Password;
use Project\Auth\Domain\ValueObject\UserId;
use Ramsey\Uuid\Uuid;
use Project\Auth\Domain\Mail\UserEmailSender;
class SignInAction
{
    private UserRepository $userRepository;
    private UserEmailSender $userEmailSender;

    public function __construct(UserRepository $userRepository, UserEmailSender $userEmailSender)
    {
        $this->userRepository = $userRepository;
        $this->userEmailSender = $userEmailSender;
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserExistException
     * @throws FailedToCreateException
     */
    public function execute(Email $email, Name $name,Password $password): void
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user) {
            throw new UserExistException("User already exists");
        }
        $id = new UserId(Uuid::uuid4()->toString());
        $date = new Date(null);
        $user = new User($id, $name, $email, $password, $date);
        $this->userRepository->create($user);

        $verificationUrl = $this->generateVerificationUrl($user);
        $this->userEmailSender->validateEmail($name, $email, $verificationUrl);
    }

    private function generateVerificationUrl(User $user): string
    {
        return config('frontend.discovery.auth') . 'api/verify-email?token=' . $user->getId()->toString();
    }
}
