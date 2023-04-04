<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if (Session::has('app_locale') && in_array(Session::get('app_locale'), Config::get('app.available_locales'))) {
            App::setLocale(Session::get('app_locale'));
        } else {
            App::setLocale(Config::get('app.locale'));
        }

        return $next($request);
    }
}
