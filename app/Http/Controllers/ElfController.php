<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Auth;

class ElfController extends Controller
{
    public function showBecomeElfForm()
    {
        if ($user = Auth::user()) {
            return view('become_elf');
        }else{
            return redirect()->route('login');
        }
    }

    public function becomeElf()
    {
        $user = Auth::user();
        $user->role_user()->create([
            'role_id' => 2,
            'rating' => 1.0,
        ]);
        $user->is_elf = true;
        $user->save();

        return redirect()->route('elf-dashboard')->with('status', 'Поздравляем, теперь вы эльф!');
    }

    public function takeOrder($order_id)
    {
        $user = Auth::user();

        $order = Order::findOrFail($order_id);
        $activeStatus = OrderStatus::where('name', 'active')->first();

        if ($order->status_id == $activeStatus->id && !$order->elf_id) {
            $inProgressStatus = OrderStatus::where('name', 'in_progress')->first();
            $order->status_id = $inProgressStatus->id;
            $order->elf_id = $user->id;
            $order->save();

            return redirect()->route('elf-dashboard')->with('message', 'Заказ успешно взят в работу!');
        }

        return redirect()->route('elf-dashboard')->with('message','Заказ уже взят в работу или недоступен.');
    }

    public function cancel($orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = Auth::user();
        $role_users = $user->role_user;

        $isElf = $role_users->some(function ($role_user) {
            return $role_user->role->name === 'elf';
        });

        if ($isElf){
            $role_user = $role_users->first(function ($role_user) {
                return $role_user->role->name === 'elf';
            });

        }else{
            return redirect()->back()->with('message','Вы не являетесь эльфом почему-то:(');
        }

        // Проверка на соответствие пользователя или эльфа
        if ($order->elf_id != $user->id || $order->user_id == $user->id) {
            return redirect()->back()->with('message','Вы не можете отменить этот заказ');
        }
        // Отмена заказа для эльфа и заказчика в статусе 'in_progress' или 'ready_for_delivery'
        elseif ($order->status->name == 'in_progress' || $order->status->name == 'ready_for_delivery') {
            $statusName = 'cancelled_by_elf';
            $ratingDecrease = $order->status->name == 'in_progress' ? 0.2 : 0.4;

            $cancellations_this_month = Order::where('user_id', $user->id)
                ->where('status_id', OrderStatus::where('name', $statusName)->first()->id)
                ->whereMonth('updated_at', now()->month)
                ->count();
            if ($cancellations_this_month == 0){
                $cancellations_this_month = 1;
            }



            $role_user->rating -= $ratingDecrease * $cancellations_this_month;
            $role_user->save();
            $order->status_id = OrderStatus::where('name', $statusName)->first()->id;
            $order->elf_id = null;
            $order->save();
        }

        return redirect()->route('elf-dashboard')->with('message', 'Заказ успешно отменен');
    }
}
