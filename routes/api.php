<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderProductController;

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

// Order routes
Route::apiResource('orders', OrderController::class);

// Product routes
Route::apiResource('products', ProductController::class);

// Order Product routes
Route::apiResource('order-products', OrderProductController::class);

// Manage products for a specific order
Route::get('orders/{order}/products', [OrderProductController::class, 'index']);
Route::post('orders/{order}/products', [OrderProductController::class, 'store']);

// New route to associate multiple products with an order
Route::post('orders/{order}/associate-products', [OrderProductController::class, 'associateProductsToOrder']);

// Optional: You may want to adjust the update and delete routes for specific order-product relationships
Route::put('orders/{order}/products/{product}', [OrderProductController::class, 'update']);
Route::delete('orders/{order}/products/{product}', [OrderProductController::class, 'destroy']);

