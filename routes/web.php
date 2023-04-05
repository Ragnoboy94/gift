<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('language/{language}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/celebrations/{celebration}', [\App\Http\Controllers\CelebrationController::class, 'show'])->name('celebrations.show');
Route::get('/terms', [\App\Http\Controllers\LegalController::class, 'showTerms'])->name('terms.show');
Route::get('/privacy-policy', [\App\Http\Controllers\LegalController::class, 'showPrivacyPolicy'])->name('policy.show');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/order/confirmation/{orderId}', [\App\Http\Controllers\OrderController::class, 'confirmation'])->name('order.confirmation');
    Route::post('/order/confirm/{orderId}', [\App\Http\Controllers\OrderController::class, 'confirm'])->name('order.confirm');
    Route::post('/order/create/{celebration}', [\App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
});
Route::get('/order/confirm/{orderId}', function () {
    return view('errors.403');
})->name('error.403');
