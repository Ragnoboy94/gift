<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ElfController extends Controller
{
    public function showBecomeElfForm()
    {
        return view('become_elf');
    }

    public function becomeElf()
    {
        $user = Auth::user();
        $user->is_elf = true;
        $user->save();

        return redirect()->route('elf-dashboard')->with('status', 'Поздравляем, теперь вы эльф!');
    }
}
