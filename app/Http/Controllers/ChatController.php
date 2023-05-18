<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);
        $elf = User::findOrFail($order->elf_id);
        $user = User::findOrFail($order->user_id);
        $messages = Message::where('order_id', $orderId)->orderBy('created_at', 'ASC')->get();
        return view('chat.show', ['order' => $order, 'messages' => $messages, 'elf' => $elf, 'user' => $user]);
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

    public function uploadFiles(Request $request, $orderId)
    {
        $request->validate([
            'photos.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $order = Order::findOrFail($orderId);
        if ($request->input('description')) {
            $order->description = $request->input('description');
            $order->save();
        }

        $uploadedPhotos = [];
        if ($request->file('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('public/photos');
                $url = Storage::url($path);

                $orderFile = new OrderFile([
                    'order_id' => $orderId,
                    'user_id' => Auth::id(),
                    'file_name' => $url,
                ]);

                $order->files()->save($orderFile);
                $uploadedPhotos[] = $url;
            }

            $message = 'Фото получено!';
        } else {
            $uploadedPhotos[] = '';
            $message = 'Описание получено!';
        }
        if ($request->input('description') && $request->file('photos')) {
            $message = "Фото и описание получены!";
        }

        // Верните ответ, например, со списком загруженных файлов или просто статусом успеха
        return response()->json(['status' => 'success', 'message' => $message, 'uploaded_photos' => $uploadedPhotos]);
    }

    public function getSavedImages(Order $order)
    {
        // Получение списка сохраненных изображений для данного заказа
        $images = $order->files; // Используйте $order->files вместо $order->images

        return response()->json($images);
    }


}
