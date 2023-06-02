<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\SocialAccount;
use App\Models\User;
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
        if ($user && !$user->email_verified_at) {
            DB::table('role_user')->where('user_id', $user->id)->delete();

            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->delete();

        }elseif ($user && !$socialAccountExists) {
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
        if ($user && $user->created_at->diffInDays(Carbon::now()) > 2 && !$user->checked) {
            $user->email_verified_at = null;
            $user->checked = true;
            $user->save();
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
