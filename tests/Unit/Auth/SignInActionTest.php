<?php

namespace Tests\Unit\Auth;

use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\MockObject\Exception;
use Project\Auth\Application\Action\SignInAction;
use Project\Auth\Domain\Repository\UserRepository;
use Project\Auth\Domain\Mail\UserEmailSender;
use Project\Auth\Domain\ValueObject\Date;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;
use Project\Auth\Domain\ValueObject\Password;
use Project\Auth\Domain\ValueObject\UserId;
use Project\Auth\Domain\Aggregate\User;
use Project\Auth\Domain\Exception\UserExistException;
use Project\Auth\Domain\Exception\FailedToCreateException;
use Project\Shared\Domain\Exception\InvalidArgumentException;

class SignInActionTest extends TestCase
{
    private UserRepository $userRepository;
    private UserEmailSender $userEmailSender;
    private SignInAction $signInAction;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userEmailSender = $this->createMock(UserEmailSender::class);
        $this->signInAction = new SignInAction($this->userRepository, $this->userEmailSender);
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserExistException
     * @throws FailedToCreateException
     */
    public function testExecuteSuccess()
    {
        $email = new Email('test@example.com');
        $name = new Name('Test User');
        $password = new Password('password');
        $userId = new UserId('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $user = new User($userId, $name, $email, $password, new Date(null));
        $this->userRepository->method('findByEmail')->willReturn(null);
        $this->userRepository->expects($this->once())->method('create')->with($user);
        $this->userEmailSender->expects($this->once())->method('validateEmail');

        $this->signInAction->execute($userId, $email, $name, $password);
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserExistException
     * @throws FailedToCreateException|Exception
     */
    public function testExecuteUserExists()
    {
        $this->expectException(UserExistException::class);

        $email = new Email('test@example.com');
        $name = new Name('Test User');
        $password = new Password('password');
        $userId = new UserId('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');
        $user = $this->createMock(User::class);

        $this->userRepository->method('findByEmail')->willReturn($user);

        $this->signInAction->execute($userId, $email, $name, $password);
    }

    /**
     * @throws InvalidArgumentException
     * @throws UserExistException
     * @throws FailedToCreateException
     */
    public function testExecuteFailedToCreate()
    {
        $this->expectException(FailedToCreateException::class);

        $email = new Email('test@example.com');
        $name = new Name('Test User');
        $password = new Password('password');
        $userId = new UserId('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');


        $this->userRepository->method('findByEmail')->willReturn(null);
        $this->userRepository->method('create')->willThrowException(new FailedToCreateException());

        $this->signInAction->execute($userId, $email, $name, $password);
    }

    /**
     * @throws FailedToCreateException
     * @throws UserExistException
     */
    public function testExecuteInvalidEmail()
    {
        $this->expectException(InvalidArgumentException::class);

        $email = new Email('invalid-email');
        $name = new Name('Test User');
        $password = new Password('password');
        $userId = new UserId('570fada1-abdb-4734-ae9a-04a5fa4cc5dc');

        $this->signInAction->execute($userId, $email, $name, $password);
    }
}
