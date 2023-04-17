<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class VkontakteController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('vkontakte')
            ->scopes(['email', 'photo_200', 'city', 'phone'])
            ->fields(['photo_200', 'city', 'phone'])
            ->with(['revoke' => 0])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $vkUser = Socialite::driver('vkontakte')->user();

        // Проверяем, существует ли пользователь с таким email
        $user = User::where('email', $vkUser->getEmail())->first();

        if (!$user) {
            // Если пользователь не найден, создаем нового
            $photo = $vkUser->getAvatar(); // Фото
            $cityName = isset($vkUser->user['city']) ? $vkUser->user['city']['title'] : null; // Город

            $city = City::where('name_ru', $cityName)->first();
            $cityId = $city ? $city->id : null;
            // Добавление города в сессию
            if ($cityId) {
                session(['city_id' => $cityId]);
            }

            // Загрузка фото на сервер
            $fileName = null;
            if ($photo) {
                $photoContent = file_get_contents($photo);
                $photoExtension = pathinfo($photo, PATHINFO_EXTENSION);
                $photoExtension = strstr($photoExtension,'?', true);
                $fileName = 'profile-photos/' . $vkUser->getId() . '.' . $photoExtension;
                Storage::disk('public')->put($fileName, $photoContent);
            }

            $user = User::create([
                'name' => $vkUser->getName(),
                'email' => $vkUser->getEmail() ?? 'email_not_provided@domain.com',
                'password' => bcrypt(Str::random(40)),
                'phone' => $vkUser->user['phone'] ?? null,
                'profile_photo_path' => $fileName, // Сохранение пути к фото
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
            session(['token' => $vkUser->token]);
        }

        $socialAccount = SocialAccount::firstOrNew(
            [
                'provider' => 'vkontakte',
                'provider_id' => $vkUser->getId(),
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
