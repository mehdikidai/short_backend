<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;


Route::get('/user', [UserController::class, 'user'])->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'store']);


Route::controller(AuthController::class)->group(function () {

    Route::post('/login', 'login')->middleware('throttle:5,1');

    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(UrlController::class)->group(function () {

        Route::get('/urls', 'index');

        Route::post('/urls', 'store');

        Route::get('/urls/{id}', 'show');

        Route::put('/urls/{id}', 'update');

        Route::delete('/urls/{id}', 'destroy');
    });

    Route::get('/search', SearchController::class);
});
