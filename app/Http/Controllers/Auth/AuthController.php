<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\SocialAccount;
use App\Models\User;
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
            // Если возникает ошибка, например, пользователь отказался предоставить разрешение, перенаправляем его на страницу регистрации.
            return redirect()->route('register');
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
                    'rating' => '2.0',
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

        Auth::login($user, true);

        return redirect('/');
    }
}
