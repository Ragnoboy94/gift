<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function vk()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function yandex()
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('register');
        }

        $user = User::where('email', $socialiteUser->getEmail())->first();
        $socialAccountExists = SocialAccount::where('provider_id', $socialiteUser->getId())->where('provider', $provider)->exists();
        if ($user && !$socialAccountExists) {
            return redirect()->route('login')->withErrors(['email' => 'Этот адрес электронной почты уже зарегистрирован через другой аккаунт. Пожалуйста, войдите с использованием соответствующего аккаунта.']);
        }
        $user = User::where('email', $socialiteUser->getEmail())->first();
        if (!$user) {
            if ($provider == 'yandex') {
                $photo = "https://avatars.yandex.net/get-yapic/{$socialiteUser->user['default_avatar_id']}/islands-200";
            } else {
                $photo = $socialiteUser->getAvatar(); // Фото
            }

            // Загрузка фото на сервер
            $fileName = null;
            if ($photo) {
                $photoContent = file_get_contents($photo);
                if ($provider == 'yandex') {
                    $photoExtension = 'jpg';
                } else {
                    $photoExtension = pathinfo($photo, PATHINFO_EXTENSION);
                    $photoExtension = strstr($photoExtension, '?', true);
                }
                $fileName = 'profile-photos/' . $socialiteUser->getId() . '.' . $photoExtension;
                Storage::disk('public')->put($fileName, $photoContent);
            }

            $user = User::create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail() ?? 'email_not_provided@domain.com',
                'password' => bcrypt(Str::random(40)),
                'phone' => $socialiteUser->user['default_phone']['number'] ?? null,
                'profile_photo_path' => $fileName, // Сохранение пути к фото
                'email_verified_at' => now(),
            ]);
            DB::table('role_user')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'role_id' => 1,
                ],
                [
                    'rating' => '1.0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        $cityName = isset($socialiteUser->user['city']) ? $socialiteUser->user['city']['title'] : null; // Город
        $city = City::where('name_en', $cityName)->first();
        $cityId = $city ? $city->id : null;
        // Добавление города в сессию
        if ($cityId) {
            session(['city_id' => $cityId]);
        }
        if ($user && $user->created_at->diffInDays(Carbon::now()) > 2 && !$user->checked) {
            $user->email_verified_at = null;
            $user->checked = true;
            $user->save();
        }
        if ($user && $user->created_at->diffInDays(Carbon::now()) > 2 && $user->checked && !is_null($user->email_verified_at) && !$user->rating_add) {
            $user->rating_add = true;
            $user->save();
            $role_user = $user->role_user->first();
            $role_user->rating += 0.6;
            $role_user->save();
        }
        $socialAccount = SocialAccount::firstOrNew(
            [
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId(),
            ],
            [
                'user_id' => $user->id,
            ]
        );

        if (!$socialAccount->exists) {
            $socialAccount->save();
        }
        $userToken = UserToken::where('user_id', $user->id)->where('active', true)->first();

        // Если у пользователя нет активного токена, создаем новый.
        if (!$userToken) {
            do {
                // Генерируем новый случайный токен
                $characters = "abcdefghijklmnopqrstuvwxyz";
                $token = str_shuffle(
                    substr(str_shuffle($characters), 0, 1) .
                    substr(str_shuffle("0123456789"), 0, 5)
                );

                // Проверяем, существует ли уже активный токен с таким значением
                $tokenExists = UserToken::where('token', $token)->where('active', true)->exists();
            } while ($tokenExists);  // Если токен существует, генерируем новый и снова проверяем

            // Создаем новый токен в БД
            $userToken = UserToken::create([
                'user_id' => $user->id,
                'token' => $token,
                'active' => true,
            ]);
        }

        Auth::login($user, true);

        return redirect('/');
    }

    public function authenticateToken(Request $request)
    {
        $token = $request->json()->get('token');

        if (!$token || strlen($token) != 6) {
            return response()->json(['message' => 'Token is required and should be 6 characters long.'], 422);
        }

        $userToken = UserToken::where('token', $token)->where('active', true)->first();

        if (!$userToken) {
            return response()->json(['message' => 'Invalid token or token already used.'], 401);
        }

        $user = User::find($userToken->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Create a personal access token for the user
        $tokenResult = $user->createToken('Personal Access Token');

        $userToken->active = false; // deactivate token after it has been used
        $userToken->save();
        do {
            // Генерируем новый случайный токен
            $characters = "abcdefghijklmnopqrstuvwxyz";
            $token = str_shuffle(
                substr(str_shuffle($characters), 0, 1) .
                substr(str_shuffle("0123456789"), 0, 5)
            );

            // Проверяем, существует ли уже активный токен с таким значением
            $tokenExists = UserToken::where('token', $token)->where('active', true)->exists();
        } while ($tokenExists);  // Если токен существует, генерируем новый и снова проверяем

        // Создаем новый токен в БД
        UserToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'active' => true,
        ]);

        return response()->json([
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }


}
