<?php

namespace App\Http\Controllers;

use App\Mail\AccountDeletionConfirmation;
use App\Models\AccountDeletionToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function sendDeletionConfirmationEmail()
    {
        $user = Auth::user();
        $last_token = AccountDeletionToken::where('user_id', $user->id)->latest()->first();
        // Генерация случайного токена
        $token = Str::random(64);

        if ($last_token->created_at->diffInMinutes(Carbon::now()) > 25) {
            // Создание нового токена в базе данных
            $deletionToken = new AccountDeletionToken([
                'token' => hash('sha256', $token),
                'expires_at' => now()->addMinutes(25)  // Токен истекает через 25 минут
            ]);

            // Связываем токен с текущим пользователем
            $deletionToken->user()->associate($user);
            $deletionToken->save();

            // Отправляем письмо с ссылкой на подтверждение
            $confirmationLink = route('confirm-delete', ['token' => $token]);
            Mail::to($user->email)->send(new AccountDeletionConfirmation($confirmationLink));
        }
            return response()->json(['message' => 'На почту отправлено письмо с подтверждением удаления аккаунта']);
    }

    public function confirmDeletion($token)
    {
        // Поиск токена в базе данных
        $deletionToken = AccountDeletionToken::where('token', hash('sha256', $token))->first();

        if (!$deletionToken) {
            // Если токен не найден, возвращаем ошибку
            return response()->json(['message' => 'Invalid token'], 400);
        }

        if ($deletionToken->expires_at < now()) {
            // Если токен истек, возвращаем ошибку
            return response()->json(['message' => 'Token expired'], 400);
        }

        // Если все в порядке, удаляем аккаунт пользователя
        $deletionToken->user->delete();

        // Возвращаем сообщение об успехе
        return response()->json(['message' => 'Account deleted successfully']);
    }
}
