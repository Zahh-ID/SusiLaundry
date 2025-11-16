<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PackageController;

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

// Guest routes
Route::get('/paket', [GuestController::class, 'showPackages']);
Route::post('/order/store', [GuestController::class, 'storeOrder']);
Route::get('/tracking/{kode}', [GuestController::class, 'trackOrder']);

// Admin routes
Route::prefix('admin')->group(function () {
    Route::resource('orders', AdminController::class);
    Route::resource('packages', PackageController::class);
});
