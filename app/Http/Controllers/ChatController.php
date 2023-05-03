<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;

class ChatController extends Controller
{
    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);

        return view('chat.show', ['order' => $order]);
    }
    public function getMessages($orderId)
    {
        $order = Order::findOrFail($orderId);
        $messages = Message::where('order_id', $orderId)->orderBy('created_at', 'ASC')->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $orderId)
    {
        $request->validate(['content' => 'required']);

        $message = new Message();
        $message->fill([
            'order_id' => $orderId,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        $message->save();

        return response()->json(['status' => 'success']);
    }

}
