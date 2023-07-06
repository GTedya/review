<?php

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ClaimController;
use App\Http\Controllers\Client\FaqController;
use App\Http\Controllers\Client\NewsController;
use App\Http\Controllers\Client\OrderController as ClientOrder;
use App\Http\Controllers\Client\PartnerController;
use App\Http\Controllers\Client\RentController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\LeasingController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Manager\OrderController as ManagerOrder;
use App\Http\Controllers\MenuController;
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
Route::get('/partners', [PartnerController::class, 'getPartner']);
Route::get('/faqs', [FaqController::class, 'getFaqs']);
Route::get('/veh_types', [VehTypeController::class, 'list']);

Route::prefix('rent')->group(function () {
    Route::get('/', [RentController::class, 'list']);
    Route::get('/{slug}', [RentController::class, 'single']);
});
Route::prefix('/news')->group(function () {
    Route::get('/', [NewsController::class, 'pagination']);
    Route::get('/{slug}', [NewsController::class, 'single']);
});

Route::get('/menu', [MenuController::class, 'list']);
Route::get('/leasings', [LeasingController::class, 'getLeasings']);
Route::get('/page/{slug}', [PageController::class, 'getPage'])->where('slug', '.*');
Route::get('/mainPage', [PageController::class, 'getMainPage']);
Route::get('/geos', [GeoController::class, 'list']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:client')->prefix('client')->group(function () {
        Route::get('/info', [UserController::class, 'info']);
        Route::prefix('order')->group(function () {
            Route::post('/edit/{id}', [ClientOrder::class, 'edit']);
            Route::get('/list', [UserController::class, 'orders']);
            Route::post('/create', [ClientOrder::class, 'create']);
            Route::get('/{id}', [ClientOrder::class, 'getOrder']);
        });

        Route::prefix('rent')->group(function () {
            Route::post('/create', [RentController::class, 'create']);
            Route::get('/history', [RentController::class, 'history']);
            Route::post('/extend/{id}', [RentController::class, 'extend']);
        });
    });


    Route::middleware('role:dealer_manager|leasing_manager')->prefix('manager')->group(function () {
        Route::prefix('/orders')->group(function () {
            Route::get('/', [ManagerOrder::class, 'orders']);
            Route::prefix('/{orderId}')->group(function () {
                Route::post('/offer', [ManagerController::class, 'sendOffer']);
                Route::post('/take_order', [ManagerOrder::class, 'takeOrder']);
                Route::get('/', [ManagerOrder::class, 'getOrder']);
            });
        });
        Route::post('/logo', [ManagerController::class, 'logoAdd']);
    });
});
