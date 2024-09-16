<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/categories/data', [\App\Http\Controllers\CategoryController::class, 'getData'])->name('categories.data')->middleware(['auth', 'verified']);
Route::resource('categories', \App\Http\Controllers\CategoryController::class)->middleware(['auth', 'verified']);
Route::get('/products/data', [\App\Http\Controllers\ProductController::class, 'getData'])->name('products.data')->middleware(['auth', 'verified']);
Route::resource('products', \App\Http\Controllers\ProductController::class)->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
