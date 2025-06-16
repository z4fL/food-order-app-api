<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/orders/check-meja', function (Request $request) {
    $meja = $request->input('meja');
    if (!$meja) {
        return response()->json(['error' => 'Meja is required'], 400);
    }

    $exists = \App\Models\Order::where('meja', $meja)
        // ->whereIn('status', ['diproses', 'diantar'])
        ->where('status', 'diproses')
        ->exists();

    return response()->json(['exists' => $exists]);
});

Route::post('/orders', [OrderController::class, 'store']);

Route::post('/pay-order/{order}', [OrderController::class, 'pay']);

Route::post('/webhook/xendit', [WebhookController::class, 'handle']);
Route::get('/produks', [ProdukController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('produks', ProdukController::class)->except(['index']);

    // Exclude 'store' and 'show' from protected resource routes
    Route::apiResource('orders', OrderController::class)->except(['store', 'show']);

    Route::get('/orders/cashier', [OrderController::class, 'indexCashier']);
    Route::patch('/orders/cashier/{order}', [OrderController::class, 'updateCashier']);
});

Route::get('/orders/{order}', [OrderController::class, 'show']);
