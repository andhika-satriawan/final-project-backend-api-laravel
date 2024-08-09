<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

Route::get('categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::post('products', [App\Http\Controllers\Api\ProductController::class, 'store']);
Route::get('products', [App\Http\Controllers\Api\ProductController::class, '__invoke']);
Route::get('products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show']);
Route::put('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'update']);
Route::delete('products/{id}', [App\Http\Controllers\Api\ProductController::class, 'destroy']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
