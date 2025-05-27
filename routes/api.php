<?php

use App\Http\Controllers\CarteController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::apiResource('products', ProductController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', action: [WishlistController::class, 'toggle']);

    //carte 
    Route::get('/cart', [CarteController::class, 'index']);
    Route::post('/cart', [CarteController::class, 'store']);
    Route::delete('/cart/{product_id}', [CarteController::class, 'destroy']);
    Route::delete('/cart', [CarteController::class , 'clear' ]);
});

