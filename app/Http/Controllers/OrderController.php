<?php

namespace App\Http\Controllers;

use App\Models\Celebration;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Role;
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
        $city_id = session('city_id');
        $city_name = City::find($city_id);
        $order = Order::findOrFail($orderId);
        $celebration = Celebration::findOrFail($order->celebration_id);
        $user = Auth::user();
        return view('orders.confirmation', compact('order', 'celebration', 'user','city_name'));
    }
    public function accept($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status_id = 2; // in_progress
        $order->elf_id = Auth::user()->id;
        $order->save();

        return response()->json(['success' => true]);
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

        return redirect()->route('orders.my_orders');
    }

    public function getOrdersByCity($city_name)
    {
        $city = City::where('name_ru', $city_name)->first();
        $user_id = auth()->id();


        if ($city) {
            $orders = \App\Models\Order::where('city_id', $city->id)
                ->where('user_id', '!=', $user_id)
                ->where('status_id','=',1)
                ->with(['user', 'celebration'])
                ->get();
            return response()->json($orders);
        }

        return response()->json([]);
    }

    public function getActiveOrdersCount()
    {
        $user_id = Auth::id();
        $activeOrdersCount = Order::where('user_id', $user_id)
            ->where(function ($query) {
                $query->whereDoesntHave('status', function ($subQuery) {
                    $subQuery->whereIn('name', ['cancelled_by_elf', 'cancelled_by_customer']);
                });
            })
            ->count();

        return $activeOrdersCount;
    }

    public function myOrders()
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->where(function ($query) {
                $query->whereDoesntHave('status', function ($subQuery) {
                    $subQuery->whereIn('name', ['cancelled_by_elf', 'cancelled_by_customer']);
                })->orWhere(function ($subQuery) {
                    $subQuery->whereHas('status', function ($subSubQuery) {
                        $subSubQuery->whereIn('name', ['cancelled_by_elf', 'cancelled_by_customer']);
                    })->where('updated_at', '>', now()->subDays(3));
                });
            })
            ->with('status')
            ->get();

        return view('orders.my_orders', compact('orders'));
    }


    public function cancel($orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = Auth::user();
        $role_user = $user->role_user->first();
        if ($order->user_id != $user->id) {
            return redirect()->back()->withErrors(['message' => 'Вы не можете отменить этот заказ']);
        }

        if ($order->status->name == 'created' || $order->status->name == 'active') {
            $order->status_id = OrderStatus::where('name', 'cancelled_by_customer')->first()->id;
            $order->save();
        } elseif ($order->status->name == 'in_progress') {


            $cancellations_this_month = Order::where('user_id', $user->id)
                ->where('status_id', OrderStatus::where('name', 'cancelled_by_customer')->first()->id)
                ->whereMonth('updated_at', now()->month)
                ->whereNotNull('elf_id')
                ->count();
            if ($cancellations_this_month == 0){
                $cancellations_this_month = 1;
            }
            $role_user->rating -= 0.2 * $cancellations_this_month;
            $role_user->save();
            $order->status_id = OrderStatus::where('name', 'cancelled_by_customer')->first()->id;
            $order->save();
        } elseif ($order->status->name == 'ready_for_delivery') {


            $cancellations_this_month = Order::where('user_id', $user->id)
                ->where('status_id', OrderStatus::where('name', 'cancelled_by_customer')->first()->id)
                ->whereMonth('updated_at', now()->month)
                ->count();
            if ($cancellations_this_month == 0){
                $cancellations_this_month = 1;
            }
            $role_user->rating -= 0.4 * $cancellations_this_month;
            $role_user->save();
            $order->status_id = OrderStatus::where('name', 'cancelled_by_customer')->first()->id;
            $order->save();
        }

        return redirect()->route('orders.my_orders')->with('message', 'Заказ успешно отменен');
    }





}
