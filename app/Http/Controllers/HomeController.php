<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $celebrations = trans('celebrations');

        return view('home', ['celebrations' => $celebrations]);
    }
    public function setCity(Request $request)
    {
        $city = $request->input('city');
        session(['city' => $city]);

        // перенаправляем на предыдущую страницу
        return redirect()->back();
    }
}
