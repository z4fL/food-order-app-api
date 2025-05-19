<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
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
        ->where('status', '!=', 'dibayar')
        ->exists();

    return response()->json(['exists' => $exists]);
});

// Publicly accessible order store and show routes
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order}', [OrderController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Exclude 'store' and 'show' from protected resource routes
    Route::apiResource('orders', OrderController::class)->except(['store', 'show']);
});
