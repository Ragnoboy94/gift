<?php

namespace App\Http\Controllers;

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
}
