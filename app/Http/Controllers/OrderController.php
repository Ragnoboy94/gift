<?php

namespace App\Http\Controllers;

use App\Models\Celebration;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class OrderController extends Controller
{
    public function create(Request $request, $celebration)
    {
        $user = Auth::user();
        $celebrationName = Celebration::where('id', $celebration)->first();
        $ratingLevel = intval($user->role_user->where('role_id', 1)->first()->rating);

        $maxOrderAmount = match ($ratingLevel) {
            1 => 1000,
            2 => 3000,
            3 => 6000,
            4 => 9000,
            default => PHP_INT_MAX,
        };
        $request->validate([
            'sum' => [
                'required',
                'numeric',
                'min:700',
                "max:$maxOrderAmount",
            ],
        ], [
            'sum.max' => 'Ваш уровень не позволяет заказывать на суммы выше ' . $maxOrderAmount . ' рублей',
            'sum.min' => 'Минимальная сумма заказа 700 рублей.',
        ]);
        $order = new Order([
            'sum' => $request->input('sum'),
            'hobby' => $request->input('hobby'),
            'user_id' => $user->id,
            'celebration_id' => $celebration,
            'status_id' => OrderStatus::where('name', 'created')->first()->id,
        ]);
        if ($celebrationName['name'] == '8 марта') {
            $order->gender = 'female';
        } else {
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
        return view('orders.confirmation', compact('order', 'celebration', 'user'));
    }

    // Декодирование невозможно
    private function encodeOrderNumber($orderId, $userId, $timestamp)
    {
        $orderNumber = $orderId . '-' . $userId . '-' . $timestamp;

        // Использование CRC32 для получения хеша, ограниченного 10 знаками
        $crc32 = crc32($orderNumber);

        // Преобразование результата в беззнаковое 32-битное целое число
        $unsignedCrc32 = $crc32 >= 0 ? $crc32 : ($crc32 + 4294967296);

        return $unsignedCrc32;
    }


    public function confirm(Request $request, $orderId)
    {
        $request->validate([
            'address' => 'required',
            'phone' => 'required',
            'due_date' => 'nullable|date',
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
        $order->updated_at = now();
        $order->status_id = OrderStatus::where('name', 'active')->first()->id;
        $order->save();
        $city_name = City::where('name_ru', $request->input('city'))->first();
        // Сохранить адрес в модели заказа
        $order->address = $request->input('address');
        $order->apartment = $request->input('apartment');
        $order->floor = $request->input('floor');
        $order->intercom = $request->input('intercom') === 'on' ? 1 : 0;;
        $order->city_id = $city_name->id;
        $encodedOrderNumber = $this->encodeOrderNumber($order->id, $order->user_id, $order->updated_at->timestamp);
        $order->order_number = $encodedOrderNumber;
        $order->deadline = $request->due_date ? $request->due_date : date('Y-m-d', strtotime('+1 month'));
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

    public function getOrdersByCity($city_name)
    {
        $city = City::where('name_ru', $city_name)->first();
        $user_id = auth()->id();


        if ($city) {
            $orders = \App\Models\Order::where('city_id', $city->id)
                ->where('user_id', '!=', $user_id)
                ->with(['user', 'celebration'])
                ->get();
            return response()->json($orders);
        }

        return response()->json([]);
    }

    public function getActiveOrdersCount()
    {
        $user_id = Auth::id();
        $activeOrdersCount = Order::where('user_id', $user_id)->count();
        return $activeOrdersCount;
    }

    public function myOrders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('status')->get();

        return view('orders.my_orders', compact('orders'));
    }
}
