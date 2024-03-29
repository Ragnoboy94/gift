<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;

class CelebrationController extends Controller
{
    public function show($celebration)
    {
        // Загрузка информации о празднике из базы данных или другого источника
        $celebrationData = $this->getCelebrationData($celebration);
        if (is_null($celebrationData)){
            return redirect()->route('home')->withErrors(['celeb' => 'Идентификатор праздника не найден.']);
        }
        $currentLanguage = app()->getLocale();
        SEOMeta::setDescription($celebrationData['description']);
        if ($currentLanguage === 'en') {
            SEOMeta::setKeywords(['gifts', 'celebration', $celebrationData['name']]);
        } else {
            SEOMeta::setKeywords(['подарки', 'праздник', $celebrationData['name']]);
        }

        return view('celebrations.show', ['celebration' => $celebrationData]);
    }

    public function getCelebrationData($celebration)
    {
        // Загружаем данные о празднике из языкового файла
        $celebrations = __('celebrations');

        // Ищем праздник с заданным идентификатором
        $celebrationData = array_filter($celebrations, function ($item) use ($celebration) {
            return $item['id'] == $celebration;
        });

        // Возвращаем данные праздника, если он найден, или null в противном случае
        return $celebrationData ? array_shift($celebrationData) : null;
    }
}
