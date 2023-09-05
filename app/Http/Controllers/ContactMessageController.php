<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        // Валидация данных формы
        $validatedData = $request->validate([
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Создание записи в базе данных
        ContactMessage::create([
            'email' => $validatedData['email'],
            'message' => $validatedData['message'],
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Сообщение успешно отправлено!');
    }

    public function index()
    {
        // Получение списка сообщений
        $messages = ContactMessage::all();

        return view('messages.index', compact('messages'));
    }
}
