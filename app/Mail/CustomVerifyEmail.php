<?php

namespace App\Mail;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmailBase
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Подтверждение электронной почты')
            ->view('emails.verify-email', ['verificationUrl' => $url]);
    }
}
