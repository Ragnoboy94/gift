<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeletionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $confirmationLink;

    public function __construct($confirmationLink)
    {
        $this->confirmationLink = $confirmationLink;
    }

    public function build()
    {
        return $this->view('emails.confirm_delete');
    }
}
