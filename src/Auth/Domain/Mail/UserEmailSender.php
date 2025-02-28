<?php

namespace Project\Auth\Domain\Mail;


use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;

interface UserEmailSender

{
    public function validateEmail(Name $name,Email $email, string $url): void;
}
