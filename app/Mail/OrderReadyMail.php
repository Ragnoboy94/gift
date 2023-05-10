<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $orderDataUrl = route('chat.show', ['orderId' => $this->order->id]);

        return $this->subject('Заказ №'.$this->order->order_number.' собран')
            ->view('emails.order_ready', ['orderDataUrl' => $orderDataUrl,'homeUrl'=>route('home')]);
    }
}
