<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/', [\App\Http\Controllers\HomeController::class,'apiIndex']);
Route::get('hollidays', [\App\Http\Controllers\HomeController::class,'apiAllHolidays']);
Route::post('auth/token', [\App\Http\Controllers\Auth\AuthController::class, 'authenticateToken']);

