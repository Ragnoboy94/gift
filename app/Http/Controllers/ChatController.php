<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);
        $messages = Message::where('order_id', $orderId)->orderBy('created_at', 'ASC')->get();
        return view('chat.show', ['order' => $order, 'messages' => $messages]);
    }

    public function getMessages($orderId, Request $request)
    {
        $lastMessageId = $request->query('lastMessageId', 0);

        $messages = Message::where('order_id', $orderId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }


    public function sendMessage(Request $request, $orderId)
    {
        $request->validate(['content' => 'required']);

        $message = new Message();
        $message->order_id = $orderId;
        $message->user_id = auth()->id();
        $message->message = $request->input('content');
        $message->save();

        return response()->json(['status' => 'success']);
    }
}
