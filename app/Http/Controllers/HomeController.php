<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $celebrations = trans('celebrations');
        $currentLanguage = app()->getLocale();

        if ($currentLanguage === 'en') {
            SEOMeta::setDescription("Welcome to our gift service! Order a unique gift for any holiday and get a surprise from the performer. We offer gifts for Birthdays, New Year's, and Women's Day.");
            SEOMeta::setKeywords(['gifts', 'gift service', 'Birthday', 'New Year', "Women's Day", 'unique gift', 'surprise', 'celebration']);
        } else {
            SEOMeta::setDescription("Добро пожаловать на наш сервис подарков! Закажите уникальный подарок для любого праздника и получите сюрприз от исполнителя. Мы предлагаем подарки для Дня рождения, Нового года и 8 марта.");
            SEOMeta::setKeywords(['подарки', 'сервис подарков', 'День рождения', 'Новый год', '8 марта', 'уникальный подарок', 'сюрприз', 'именинник', 'праздник']);
        }
        return view('home', ['celebrations' => $celebrations]);
    }
}
