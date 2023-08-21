<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProblem;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderProblemController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $validated = $request->validate([
            'description' => 'required|string',
        ]);

        // Изменение статуса заказа
        $order->status_id = OrderStatus::where('name', 'problem_with_order')->first()->id;
        $order->save();

        // Создание записи о проблеме
        OrderProblem::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'description' => $validated['description'],
            'resolved' => false,
        ]);

        if ($order->user_id == Auth::user()->id) {
            // Перенаправление на страницу моих заказов
            return redirect()->route('orders.my_orders')->with('message', 'Проблема зарегистрирована. Ожидайте решения');
        }else{
            return redirect()->route('elf-dashboard')->with('message', 'Проблема зарегистрирована. Ожидайте решения');
        }
    }
}
