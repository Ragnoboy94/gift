<?php

namespace App\Http\Controllers;

use App\Models\Celebration;
use App\Models\City;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class OrderController extends Controller
{
    public function create(Request $request, $celebration)
    {
        $user = Auth::user();
        $celebrationName = __('celebrations.' . $celebration)['name'];
        $celebrationModel = Celebration::where('name', $celebrationName)->first();

        $order = new Order([
            'sum' => $request->input('sum'),
            'hobby' => $request->input('hobby'),
            'user_id' => $user->id, // Связываем модели с помощью их ID напрямую
            'celebration_id' => $celebrationModel->id, // Связываем модели с помощью их ID напрямую
        ]);
        if ($celebrationName !== '8 марта') {
            $order->gender = $request->input('gender');
        }

        $result = $order->save();
        if (!$result) {
            dd($order->withErrors()); // Выведет ошибки, если они есть
        }

        return redirect()->route('order.confirmation', ['orderId' => $order->id]);
}
    public function confirmation($orderId)
    {
        $order = Order::findOrFail($orderId);
        $celebration = Celebration::findOrFail($order->celebration_id);
        $user = Auth::user();
        return view('orders.confirmation', compact('order','celebration','user'));
    }

    public function confirm(Request $request, $orderId)
    {
        $request->validate([
            'address' => 'required',
            'phone' => 'required',
        ]);
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($request->input('phone'), 'RU');
            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                return redirect()->back()->withErrors(['phone' => 'Неверный формат номера телефона']);
            }
        } catch (NumberParseException $e) {
            return redirect()->back()->withErrors(['phone' => 'Неверный формат номера телефона']);
        }
        // Найти заказ по orderId
        $order = Order::findOrFail($orderId);
        $city_name = City::where('name_ru', $request->input('city'))->first();
        // Сохранить адрес в модели заказа
        $order->address = $request->input('address');
        $order->apartment = $request->input('apartment');
        $order->floor = $request->input('floor');
        $order->intercom = $request->input('intercom') === 'on' ? 1 : 0;;
        $order->city_id = $city_name->id;
        $order->save();


        // Здесь добавьте логику для перехода к оплате
        // Например, в зависимости от вашей системы оплаты, вы можете создать платеж и получить URL-адрес платежной страницы
        $user = Auth::user();
        $user->phone = $request->input('phone');
        $user->save();
        // Предполагая, что у вас есть URL-адрес платежной страницы
        $paymentUrl = "https://example.com/payment/{$order->id}";

        // Перенаправить пользователя на платежную страницу
        return redirect($paymentUrl);

        return redirect()->route('payment', ['orderId' => $order->id]);
    }
}
