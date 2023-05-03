<?php

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\NewsController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\Manager\ManagerController;
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
    Route::prefix('/news')->group(function () {
        Route::get('/', [NewsController::class, 'pagination']);
        Route::get('/{slug}', [NewsController::class, 'single']);
    });
    Route::get('/geos', [GeoController::class, 'list']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:client')->prefix('client')->group(function () {
        Route::get('/info', [UserController::class, 'info']);
        Route::get('/orders', [UserController::class, 'orders']);
    });

    Route::middleware('role:dealer_manager|leasing_manager')->prefix('manager')->group(function () {
        Route::get('/orders', [OrderController::class, 'orders']);
        Route::post('/logo', [ManagerController::class, 'logoAdd']);
    });
});
