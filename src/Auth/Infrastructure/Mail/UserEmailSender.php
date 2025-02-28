<?php

namespace Project\Auth\Infrastructure\Mail;


use App\Services\MailService;
use Project\Auth\Domain\Mail\UserEmailSender as UserEmailSenderInterface;
use Project\Auth\Domain\ValueObject\Email;
use Project\Auth\Domain\ValueObject\Name;
use Project\Auth\Infrastructure\Mail\Mailable\ValidateEmailMailable;

class UserEmailSender implements UserEmailSenderInterface
{
    private MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function validateEmail(Name $name, Email $email, string $url): void
    {
        $this->mailService->send(new ValidateEmailMailable($name->toString(), $email->toString(), $url));
    }
}
