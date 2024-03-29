<?php

namespace App\Http\Controllers;

use App\Mail\OrderReadyMail;
use App\Models\Celebration;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;


class OrderController extends Controller
{
    public function create(Request $request, $celebration)
    {
        $user = Auth::user();
        $activeOrdersCount = $this->getActiveOrdersCount();

        if ($activeOrdersCount >= 3) {
            return redirect()->back()->withErrors(['error' => __('messages.order_limit_reached')]);
        }
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
            'sum.max' => __('trans.summabig1') . $maxOrderAmount . __('modal.rubles'),
            'sum.min' => __('cont.min_sum'),
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
            dd($order->withErrors());
        }

        return redirect()->route('order.confirmation', ['orderId' => $order->id]);
    }

    public function confirmation($orderId)
    {
        $city_id = session('city_id');
        $city_name = City::find($city_id);
        $order = Order::findOrFail($orderId);
        $celebration = Celebration::findOrFail($order->celebration_id);
        if (session()->get('app_locale') == 'en') {
            $celebration->description = app(CelebrationController::class)->getCelebrationData($order->celebration_id)['description'];
        }
        $user = Auth::user();
        return view('orders.confirmation', compact('order', 'celebration', 'user', 'city_name'));
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


        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($request->input('phone'), 'RU');
            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                return redirect()->back()->withErrors(['phone' => __('cont.wrong_phone')]);
            }
        } catch (NumberParseException $e) {
            return redirect()->back()->withErrors(['phone' => __('cont.wrong_phone')]);
        }
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
                ->where('status_id', '=', 1)
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
                    $subQuery->whereIn('name', ['cancelled_by_elf', 'cancelled_by_customer', 'finished']);
                });
            })
            ->count();

        return $activeOrdersCount;
    }

    function pluralizeRubles($number)
    {
        $remainder100 = $number % 100;
        $remainder10 = $number % 10;

        if ($remainder100 >= 11 && $remainder100 <= 19) {
            return __('trans.rubles');
        } elseif ($remainder10 == 1) {
            return __('trans.ruble');
        } elseif ($remainder10 >= 2 && $remainder10 <= 4) {
            return __('trans.rublya');
        } else {
            return __('trans.rubles');
        }
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
            ->with(['status' => function ($query) {
                $query->addSelect(['id', 'name', 'display_name','display_name_en']);
            }])
            ->with(['celebration' => function ($query) {
                $query->select(['id', 'image']);
            }])
            ->select(['id', 'order_number', 'sum', 'status_id', 'deadline', 'created_at', 'celebration_id'])
            ->get();
        foreach ($orders as $order) {
            $sum_order = $order->sum;
            $sum_elf = 200 + (($sum_order - 625) / 100 * 15);
            $sum_work = $sum_order - $sum_elf;

            $order->sum_elf = $sum_elf;
            $order->sum_work = $sum_work;
            $order->sum_rubles = $this->pluralizeRubles($sum_order);
            $order->sum_elf_rubles = $this->pluralizeRubles($sum_elf);
            $order->sum_work_rubles = $this->pluralizeRubles($sum_work);
            if (session()->get('app_locale') == 'en') {
                $order->status->display_name = $order->status->display_name_en;
            }
            $order->user_t_id = $user->id;
            if ($order->status_id == 8 && $order->problems->resolved && $order->problems->user_id == $user->id){
                $order->comment = $order->problems->comments->comment;
            }
        }
        return view('orders.my_orders', compact('orders'));
    }


    public function cancel($orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = Auth::user();
        $role_user = $user->role_user->first();

        // Проверка на соответствие пользователя или эльфа
        if (($order->elf_id == $user->id && !is_null($order->elf_id)) || ($order->user_id != $user->id)) {
            return redirect()->back()->withErrors(['message' => __('cont.can_not_cancel')]);
        }

        // Отмена заказа для заказчика
        if (($order->status->name == 'created' || $order->status->name == 'active' || $order->status->name == 'cancelled_by_elf')) {
            $order->status_id = OrderStatus::where('name', 'cancelled_by_customer')->first()->id;
            $order->save();
        } elseif ($order->status->name == 'in_progress' || $order->status->name == 'ready_for_delivery') {
            $statusName = 'cancelled_by_customer';
            $ratingDecrease = $order->status->name == 'in_progress' ? 0.2 : 0.4;

            $cancellations_this_month = Order::where('user_id', $user->id)
                ->where('status_id', OrderStatus::where('name', $statusName)->first()->id)
                ->whereMonth('updated_at', now()->month)
                ->count();
            if ($cancellations_this_month == 0) {
                $cancellations_this_month = 1;
            }
            $role_user->rating -= $ratingDecrease * $cancellations_this_month;
            $role_user->save();
            $order->status_id = OrderStatus::where('name', $statusName)->first()->id;
            $order->save();
        }elseif ($order->status->name == 'problem_with_order' && $order->problems->resolved){
            $statusName = 'cancelled_by_customer';
            $order->status_id = OrderStatus::where('name', $statusName)->first()->id;
            $order->save();
        }

        return redirect()->route('orders.my_orders')->with('message', __('cont.cancel_success'));
    }

    public function showDataPage($orderId)
    {
        return view('order_data_page');
    }

    public function sendEmailWithOrderData($order)
    {
        try {
            Mail::to($order->user->email)->send(new OrderReadyMail($order));
            return true;
        } catch (\Exception $e) {
            Log::error('Error sending email for order ID: ' . $order->id . ' - ' . $e->getMessage());
        }
    }

    public function sendOrderReady($orderId)
    {
        $order = Order::findOrFail($orderId);

        $inProgressStatus = OrderStatus::where('name', 'in_progress')->first()->id;
        if ($order->status_id !== $inProgressStatus) {
            return redirect()->back()->with('message', __('cont.text1'));
        }


        // Отправьте электронное письмо
        if ($this->sendEmailWithOrderData($order)) {
            $readyForDeliveryStatus = OrderStatus::where('name', 'ready_for_delivery')->first()->id;
            $order->status_id = $readyForDeliveryStatus;
            $order->save();
            return redirect()->route('chat.show', ['orderId' => $orderId]);
        } else {
            return redirect()->back()->with('message', __('cont.error_send'));
        }


    }

    public function updatePhoneVisibility(Request $request, Order $order)
    {
        $order->update(['phone_visible' => true]);

        return redirect()->back()->with('message', __('cont.text2'));
    }

    public function finishOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Убедитесь, что заказ в правильном статусе перед окончанием
        $validStatuses = ['ready_for_delivery','problem_with_order'];
        if (!in_array($order->status->name, $validStatuses)) {
            return redirect()->back()->with('error_message', __('cont.order_not_done'));
        }
        $finishedStatus = OrderStatus::where('name', 'finished')->first();
        if ($order->status->name != 'problem_with_order'){


            // Увеличиваем рейтинг пользователя
            $ratingIncrease = 0.2;

            $elf = User::find($order->elf_id);
            $role_elf = $elf->role_user()->where('role_id', 2)->first();

            // Получаем количество заказов, завершенных этим эльфом в этом месяце
            $completed_orders_this_monthelf = Order::where('elf_id', $elf->id)
                ->where('status_id', $finishedStatus->id)
                ->whereMonth('updated_at', now()->month)
                ->count();

            // Ограничиваем количество успешных заказов для увеличения рейтинга до 5
            $completed_orders_this_monthelf = min($completed_orders_this_monthelf, 5);
            if ($completed_orders_this_monthelf == 0) {
                $completed_orders_this_monthelf = 1;
            }
            // Увеличиваем рейтинг эльфа
            $role_elf->rating += $ratingIncrease * $completed_orders_this_monthelf;
            $role_elf->save();
        }
        $order->status_id = $finishedStatus->id;
        $order->save();

        return redirect()->route('orders.my_orders')->with('message', __('cont.order_success'));
    }

    public function markAsPaid($orderId)
    {
        $order = Order::findOrFail($orderId);

        $order->paid = true;
        $order->save();
        $finishedStatus = OrderStatus::where('name', 'finished')->first();
        if ($order->status->name != 'problem_with_order') {
            $user = User::find($order->user_id);
            $role_user = $user->role_user->first();

            // Получаем количество заказов, завершенных этим пользователем в этом месяце
            $completed_orders_this_month = Order::where('user_id', $user->id)
                ->where('status_id', $finishedStatus->id)
                ->whereMonth('updated_at', now()->month)
                ->count();

            // Ограничиваем количество успешных заказов для увеличения рейтинга до 5
            $completed_orders_this_month = min($completed_orders_this_month, 5);
            if ($completed_orders_this_month == 0) {
                $completed_orders_this_month = 1;
            }
            // Увеличиваем рейтинг пользователя
            $ratingIncrease = 0.2;
            $role_user->rating += $ratingIncrease * $completed_orders_this_month;
            $role_user->save();
        }else{
            $order->status_id = $finishedStatus->id;
            $order->save();
        }
        return redirect()->route('elf-dashboard')->with('message', __('cont.order_payed'));
    }
}
