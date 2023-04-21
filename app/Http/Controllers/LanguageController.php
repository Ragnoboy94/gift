<?php

namespace App\Http\Controllers;


class LanguageController extends Controller
{
    public function switch($language)
    {
        // Сохраняем выбранный язык в сессии
        session(['app_locale' => $language]);

        // Возвращаем пользователя на предыдущую страницу
        return redirect()->back();
    }
}
