<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/user', [UserController::class, 'user'])->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'store']);



Route::controller(AuthController::class)->group(function () {

    Route::post('/login', 'login')->middleware('throttle:5,1');

    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(UrlController::class)->prefix('urls')->group(function () {

    Route::get('/', 'index');

    Route::post('/', 'store');

    Route::get('/{id}', 'show');
    
})->middleware('auth:sanctum');
