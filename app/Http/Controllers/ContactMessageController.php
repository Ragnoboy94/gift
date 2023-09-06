<?php

namespace App\Http\Controllers;

use App\Mail\ContentEmailMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $content = ContactMessage::create([
            'email' => $validatedData['email'],
            'message' => $validatedData['message'],
            'created_at' => now(),
        ]);

        try {
            Mail::to("help@gift-secrets.ru")->send(new ContentEmailMail($content));
            return redirect()->back()->with('message', 'Сообщение успешно отправлено!');
        } catch (\Exception $e) {
            Log::error('Error sending email for ID: ' . $content->id . ' - ' . $e->getMessage());
            return redirect()->back()->with('error', 'Сообщение зарегистрировано!');
        }


    }

    public function index()
    {
        // Получение списка сообщений
        $messages = ContactMessage::all();

        return view('messages.index', compact('messages'));
    }
}
