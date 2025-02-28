<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Project\Auth\Infrastructure\Mail\Mailable\ValidateEmailMailable;

class ValidateEmailMailableTest extends TestCase
{
    public function testEmailIsSent()
    {
        Mail::fake();

        $name = 'John Doe';
        $email = 'john.doe@example.com';
        $url = 'https://example.com/verify?token=12345';

        Mail::to($email)->send(new ValidateEmailMailable($name, $email, $url));

        Mail::assertSent(ValidateEmailMailable::class, function ($mail) use ($name, $email, $url) {
            return $mail->hasTo($email) &&
                   $mail->name === $name &&
                   $mail->url === $url;
        });
    }
}
