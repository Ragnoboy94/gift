<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Order;
use Artesaos\SEOTools\Facades\SEOMeta;
use Nette\Utils\DateTime;

class HomeController extends Controller
{
    public function index()
    {
        // Проверяем, существует ли сессионное значение 'city_id'
        if (!session()->has('city_id')) {
            // Если значение отсутствует, устанавливаем его
            session(['city_id' => 1]);
        }
        $celebrations = trans('celebrations');

        // Получение текущей даты
        $currentDate = new DateTime();
        $oneWeekBefore = clone $currentDate;
        // Вычисление даты, которая находится на одной неделе до текущей
        $oneWeekBefore->modify('-1 week');
        $threeWeeksAfter = clone $currentDate;
        // Вычисление даты, которая находится на трех неделях после текущей
        $threeWeeksAfter->modify('+3 weeks');

        // Фильтрация списка праздников, чтобы показывать только те, что удовлетворяют заданным условиям
        $filteredHolidays = array_filter($celebrations, function ($holiday) use ($oneWeekBefore, $threeWeeksAfter) {
            // Если дата праздника равна null, включаем его в список
            if ($holiday['date'] === null) {
                return true;
            }

            $holidayDate = DateTime::createFromFormat('m-d', $holiday['date']);
            // Проверяем, находится ли дата праздника в заданном диапазоне
            return $holidayDate >= $oneWeekBefore && $holidayDate <= $threeWeeksAfter;
        });
        // Выводим первые три праздника из отфильтрованного списка
        $displayedHolidays = array_slice($filteredHolidays, 0, 3);

        // Получение названий и ключевых слов текущих праздников
        $holidayNames = [];
        $holidayKeywords = [];

        foreach ($displayedHolidays as $holiday) {
            $holidayNames[] = $holiday['name'];
            if (isset($holiday['keywords'])) {
                $holidayKeywords = array_merge($holidayKeywords, $holiday['keywords']);
            }
        }

        $holidayNames = implode(', ', $holidayNames);
        $holidayKeywords = implode(', ', array_unique($holidayKeywords));

        $currentLanguage = app()->getLocale();

        if ($currentLanguage === 'en') {
            SEOMeta::setDescription("Welcome to our gift service! Order a unique gift for these holidays: $holidayNames. Get a surprise from the Elf.");
            SEOMeta::setKeywords(['gifts', 'gift service', 'unique gift', 'surprise', 'celebration', $holidayKeywords]);
        } else {
            SEOMeta::setDescription("Добро пожаловать на наш сервис подарков! Закажите уникальный подарок для этих праздников: $holidayNames и получите сюрприз от Эльфа.");
            SEOMeta::setKeywords(['подарки', 'сервис подарков', 'уникальный подарок', 'сюрприз', 'праздник', $holidayKeywords]);
        }
        return view('home', ['celebrations_3' => $displayedHolidays]);
    }
    public function elfDashboard()
    {
        $city_id = session('city_id');
        $city_name = City::find($city_id);
        $user_id = auth()->id();

        $activeOrders = Order::whereHas('status', function ($query) {
            $query->where('name', 'in_progress');
        })->where('elf_id', $user_id)
            ->with(['user', 'celebration'])->get();

        foreach ($activeOrders as $order) {
            $sum_order = $order->sum;
            $sum_elf = 200 + (($sum_order - 625) / 100 * 15);
            $sum_work = $sum_order - $sum_elf;

            $order->sum_elf = $sum_elf;
            $order->sum_work = $sum_work;
        }

        return view('elf_dashboard', compact('city_name', 'activeOrders', 'sum_elf', 'sum_work'));
    }
}
