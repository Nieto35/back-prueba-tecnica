<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailService
{

    public function send(Mailable $mailable): void
    {
        Mail::send($mailable);
    }
}
