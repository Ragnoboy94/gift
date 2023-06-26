<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\UserToken;
use Artesaos\SEOTools\Facades\SEOMeta;
use Nette\Utils\DateTime;

class HomeController extends Controller
{
    public function index()
    {
        if (!session()->has('city_id')) {
            session(['city_id' => 1]);
        }
        $celebrations = trans('celebrations');

        $currentDate = new DateTime();
        $oneWeekBefore = clone $currentDate;
        $oneWeekBefore->modify('-1 week');
        $threeWeeksAfter = clone $currentDate;
        $threeWeeksAfter->modify('+4 weeks');

        usort($celebrations, function ($a, $b) {
            if ($a['date'] === null && $b['date'] === null) {
                return 0;
            }
            if ($a['date'] === null) {
                return 1;
            }
            if ($b['date'] === null) {
                return -1;
            }
            return strcmp($a['date'], $b['date']);
        });

        $filteredHolidays = array_filter($celebrations, function ($holiday) use ($oneWeekBefore, $threeWeeksAfter) {
            if ($holiday['date'] === null) {
                return true;
            }

            $holidayDate = DateTime::createFromFormat('m-d', $holiday['date']);
            return $holidayDate >= $oneWeekBefore && $holidayDate <= $threeWeeksAfter;
        });
        $displayedHolidays = array_slice($filteredHolidays, 0, 3);
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
        if (auth()->id()) {
            $userToken = UserToken::where('user_id', auth()->id())->where('active', true)->first();
            session(['userToken' => $userToken->token]);
        }
        return view('home', ['celebrations_3' => $displayedHolidays]);
    }

    public function elfDashboard()
    {
        $city_id = session('city_id');
        $city_name = City::find($city_id);
        $user_id = auth()->id();

        $activeOrders = Order::whereIn('status_id', [
            OrderStatus::where('name', 'in_progress')->first()->id,
            OrderStatus::where('name', 'ready_for_delivery')->first()->id,
            OrderStatus::where('name', 'finished')->first()->id,
        ])->where('elf_id', $user_id)
            ->where('paid', false)
            ->with(['user', 'celebration'])->get();
        $recentOrders = Order::whereIn('status_id', [
            OrderStatus::where('name', 'cancelled_by_customer')->first()->id,
        ])->where('elf_id', $user_id)
            ->where('updated_at', '>', now()->subDays(3))
            ->with(['user', 'celebration'])->get();
        $orders = $activeOrders->merge($recentOrders);
        foreach ($orders as $order) {
            $sum_order = $order->sum;
            $sum_elf = 200 + (($sum_order - 625) / 100 * 15);
            $sum_work = $sum_order - $sum_elf;

            $order->sum_elf = $sum_elf;
            $order->sum_work = $sum_work;
        }

        return view('elf_dashboard', compact('city_name', 'orders'));
    }

    public function apiIndex()
    {
        $celebrations = trans('celebrations');

        $currentDate = new DateTime();
        $oneWeekBefore = clone $currentDate;
        $oneWeekBefore->modify('-1 week');
        $threeWeeksAfter = clone $currentDate;
        $threeWeeksAfter->modify('+4 weeks');

        usort($celebrations, function ($a, $b) {
            if ($a['date'] === null && $b['date'] === null) {
                return 0;
            }
            if ($a['date'] === null) {
                return 1;
            }
            if ($b['date'] === null) {
                return -1;
            }
            return strcmp($a['date'], $b['date']);
        });

        $filteredHolidays = array_filter($celebrations, function ($holiday) use ($oneWeekBefore, $threeWeeksAfter) {
            if ($holiday['date'] === null) {
                return true;
            }

            $holidayDate = DateTime::createFromFormat('m-d', $holiday['date']);
            return $holidayDate >= $oneWeekBefore && $holidayDate <= $threeWeeksAfter;
        });

        $displayedHolidays = array_slice($filteredHolidays, 0, 3);

        // modify each holiday to include the image path
        foreach ($displayedHolidays as $key => $holiday) {
            $displayedHolidays[$key]['image'] = asset('images/' . pathinfo($holiday['image'], PATHINFO_FILENAME) . '_small.jpg');
            $displayedHolidays[$key]['img'] = $holiday['image'];
        }

        return response()->json($displayedHolidays);
    }

    public function apiAllHolidays()
    {
        $celebrations = trans('celebrations');


        // modify each holiday to include the image path
        foreach ($celebrations as $key => $holiday) {
            $celebrations[$key]['image'] = asset('images/' . pathinfo($holiday['image'], PATHINFO_FILENAME) . '_small.jpg');
            $celebrations[$key]['img'] = $holiday['image'];
        }

        return response()->json($celebrations);
    }

}
