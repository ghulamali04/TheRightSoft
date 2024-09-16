<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class,'register']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/categories', [ApiController::class, 'getCategories']);
    Route::get('/categories/{id}', [ApiController::class, 'getCategory']);
    Route::get('/products', [ApiController::class, 'getProducts']);
    Route::get('/products/{id}', [ApiController::class, 'getProduct']);
});
