<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
// Nanti kita akan tambahkan ProductController di sini

// Auth routes (Tidak dilindungi, bisa diakses bebas)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (Dilindungi oleh Sanctum, wajib pakai Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('products', ProductController::class);
    
    // Nanti route Product akan dimasukkan ke sini juga
    // Route::apiResource('products', ProductController::class);
});