<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function showTerms()
    {
        return view('legal.terms');
    }

    public function showPrivacyPolicy()
    {
        return view('legal.privacy_policy');
    }
}
