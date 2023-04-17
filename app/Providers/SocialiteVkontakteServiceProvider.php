<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteManager;

class SocialiteVkontakteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(SocialiteManager::class, function ($socialite) {
            $socialite->extend('vkontakte', function ($app) {
                $config = $app['config']['services.vkontakte'];
                return Socialite::buildProvider(\App\Services\Provider::class, $config);
            });
        });
    }
}
