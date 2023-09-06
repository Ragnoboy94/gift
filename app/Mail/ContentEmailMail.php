<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContentEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    public function __construct(ContactMessage $content)
    {
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject('Сообщение с сайта №'.$this->content->id.'.')
            ->view('emails.contact_email', ['id' => $this->content->id,'email'=>$this->content->email,'content'=>$this->content->message]);
    }
}
