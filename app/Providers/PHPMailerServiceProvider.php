<?php

namespace App\Providers;
use App\Mail\PHPMailerTransport;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class PHPMailerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->resolving(MailManager::class, function (MailManager $mailManager) {
            $mailManager->extend('phpmailer', function () {
                return new PHPMailerTransport();
            });
        });
    }
}
