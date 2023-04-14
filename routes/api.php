<?php

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Manager\OrderController;
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
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => '/client', 'middleware' => 'role:client'], function () {
        Route::get('/info', [UserController::class, 'info']);
        Route::get('/orders', [UserController::class, 'orders']);
    });

    Route::group(['prefix' => '/manager', 'middleware' => 'role:dealer_manager|leasing_manager'], function () {
        Route::get('/orders', [OrderController::class, 'orders']);
    });
});
