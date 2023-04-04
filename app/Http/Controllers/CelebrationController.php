<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CelebrationController extends Controller
{
    public function show($celebration)
    {
        // Загрузка информации о празднике из базы данных или другого источника
        $celebrationData = $this->getCelebrationData($celebration);
        $celebrations = trans('celebrations');
        return view('celebrations.show', ['celebration' => $celebrationData,'celebrations' => $celebrations]);
    }

    private function getCelebrationData($celebration)
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
