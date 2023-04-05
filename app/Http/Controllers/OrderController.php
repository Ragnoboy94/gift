<?php

namespace App\Http\Controllers;

use App\Models\Celebration;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('orders.confirmation', compact('order','celebration'));
    }

    public function confirm(Request $request, $orderId)
    {
        $request->validate([
            'address' => 'required'
        ]);

        // Найти заказ по orderId
        $order = Order::findOrFail($orderId);

        // Сохранить адрес в модели заказа
        $order->address = $request->input('address');
        $order->save();

        // Здесь добавьте логику для перехода к оплате
        // Например, в зависимости от вашей системы оплаты, вы можете создать платеж и получить URL-адрес платежной страницы

        // Предполагая, что у вас есть URL-адрес платежной страницы
        $paymentUrl = "https://example.com/payment/{$order->id}";

        // Перенаправить пользователя на платежную страницу
        return redirect($paymentUrl);

        return redirect()->route('payment', ['orderId' => $order->id]);
    }
}
