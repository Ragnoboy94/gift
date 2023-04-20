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
}
