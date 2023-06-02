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
Route::get('/auth/vk', [\App\Http\Controllers\Auth\AuthController::class, 'vk'])->name('auth.vk');
Route::get('/auth/yandex', [\App\Http\Controllers\Auth\AuthController::class, 'yandex'])->name('auth.yandex');
Route::get('/auth/google', [\App\Http\Controllers\Auth\AuthController::class, 'google'])->name('auth.google');
Route::get('login/{provider}/callback', [\App\Http\Controllers\Auth\AuthController::class, 'handleCallback']);
Route::get('language/{language}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/celebrations/{celebration}', [\App\Http\Controllers\CelebrationController::class, 'show'])->name('celebrations.show');
Route::get('/terms', [\App\Http\Controllers\LegalController::class, 'showTerms'])->name('terms1.show');
Route::get('/privacy-policy', [\App\Http\Controllers\LegalController::class, 'showPrivacyPolicy'])->name('policy.show');
Route::get('/become-elf', [\App\Http\Controllers\ElfController::class,'showBecomeElfForm'])->name('become-elf');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/user/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.show');
    Route::get('/order/confirmation/{orderId}', [\App\Http\Controllers\OrderController::class, 'confirmation'])->name('order.confirmation');
    Route::post('/order/confirm/{orderId}', [\App\Http\Controllers\OrderController::class, 'confirm'])->name('order.confirm');
    Route::post('/order/create/{celebration}', [\App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
    Route::post('/become-elf',  [\App\Http\Controllers\ElfController::class,'becomeElf'])->name('become-elf.submit');
    Route::get('/active-orders-count', [\App\Http\Controllers\OrderController::class, 'getActiveOrdersCount'])->name('orders.active_count');
    Route::get('/my-orders', [\App\Http\Controllers\OrderController::class, 'myOrders'])->name('orders.my_orders');
    Route::get('/order/cancel/{orderId}', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('order.cancel');
    Route::get('/order/data/{orderId}', [\App\Http\Controllers\OrderController::class,'showDataPage'])->name('order.show_data');
    Route::get('chat/{orderId}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{orderId}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages']);
    Route::post('/chat/{orderId}/send', [\App\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::put('/orders/{order}/update_phone_visibility', [\App\Http\Controllers\OrderController::class, 'updatePhoneVisibility'])->name('orders.update_phone_visibility');
    Route::post('/orders/{order}/finish', [\App\Http\Controllers\OrderController::class, 'finishOrder'])->name('orders.finish');
    Route::post('/order-problem/{orderId}', [\App\Http\Controllers\OrderProblemController::class, 'store'])->name('order-problem.store');
    Route::middleware('auth')->post('/account/delete', [\App\Http\Controllers\AccountController::class, 'sendDeletionConfirmationEmail'])->name('account.delete');
    Route::get('/account/delete/confirm/{token}', [\App\Http\Controllers\AccountController::class, 'confirmDeletion'])
        ->name('confirm-delete');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'is_elf'
])->group(function () {
    Route::get('/elf-dashboard', [App\Http\Controllers\HomeController::class, 'elfDashboard'])->name('elf-dashboard');
    Route::get('/get-orders-by-city/{city_name}', [\App\Http\Controllers\OrderController::class, 'getOrdersByCity']);
    Route::get('/active-orders', [\App\Http\Controllers\OrderController::class, 'getActiveOrders'])->name('orders.active_orders');
    Route::get('/elf/take-order/{order_id}', [\App\Http\Controllers\ElfController::class, 'takeOrder'])->name('elf.take-order');
    Route::get('/elf/cancel/{orderId}', [\App\Http\Controllers\ElfController::class, 'cancel'])->name('elf.cancel');
    Route::get('/send-order-ready/{orderId}', [\App\Http\Controllers\OrderController::class, 'sendOrderReady'])->name('send-order-ready');
    Route::post('/chat/{orderId}/upload', [\App\Http\Controllers\ChatController::class, 'uploadFiles'])->middleware('auth')->name('upload_files');
    Route::get('/orders/{order}/images', [\App\Http\Controllers\ChatController::class, 'getSavedImages'])->name('get_saved_images');
    Route::post('/orders/{order}/mark-as-paid', [\App\Http\Controllers\OrderController::class, 'markAsPaid'])->name('orders.mark_as_paid');

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'is_admin'
])->group(function () {
    Route::get('/admin-dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/translate/{sourceLanguage}', [\App\Http\Controllers\AdminController::class, 'generateLanguagePacks'])->name('translate.generate');
});

Route::get('/order/confirm/{orderId}', function () {
    return view('errors.403');
})->name('error.403');
