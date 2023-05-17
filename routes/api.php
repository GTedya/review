<?php

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ClaimController;
use App\Http\Controllers\Client\NewsController;
use App\Http\Controllers\Client\OrderController as ClientOrder;
use App\Http\Controllers\Client\RentController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Manager\OrderController as ManagerOrder;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VehTypeController;
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
Route::post('/claim', [ClaimController::class, 'putClaim']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/news')->group(function () {
        Route::get('/', [NewsController::class, 'pagination']);
        Route::get('/{slug}', [NewsController::class, 'single']);
    });
    Route::get('/page/{slug}', [PageController::class, 'getPage']);

    Route::get('/geos', [GeoController::class, 'list']);

    Route::get('/veh_types', [VehTypeController::class, 'list']);

    Route::middleware('role:client')->prefix('client')->group(function () {
        Route::get('/info', [UserController::class, 'info']);
        Route::prefix('order')->group(function () {
            Route::get('/list', [UserController::class, 'orders']);
            Route::post('/create', [ClientOrder::class, 'create']);
        });

        Route::post('/rent', [RentController::class, 'create']);
    });


    Route::middleware('role:dealer_manager|leasing_manager')->prefix('manager')->group(function () {
        Route::get('/orders', [ManagerOrder::class, 'orders']);
        Route::post('/logo', [ManagerController::class, 'logoAdd']);
    });
});
