<?php

namespace Project\Auth\Domain\Aggregate;

use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Date;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;
use Project\Auth\Domain\ValueObject\Password;
use Project\Auth\Domain\ValueObject\UserId;
use App\Models\User as UserModel;

class User
{
    private UserId $id;
    private Name $name;
    private Email $email;
    private Password $password;
    private Date $emailVerifiedAt;

    public function __construct(
        UserId $id,
        Name $name,
        Email $email,
        Password $password,
        Date $emailVerifiedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromEloquentModel(UserModel $model): self
    {
        return new self(
            new UserId($model->id),
            new Name($model->name),
            new Email($model->email),
            new Password($model->password),
            new Date($model->email_verified_at)
        );
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getEmailVerifiedAt(): Date
    {
        return $this->emailVerifiedAt;
    }


}
