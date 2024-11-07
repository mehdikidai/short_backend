<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->group(function () {

	Route::post('/login', 'login')->middleware('throttle:5,10');

	Route::post('/logout', 'logout')->middleware('auth:sanctum');

	Route::post('/password/send-reset-code', 'sendResetCodeEmail')->middleware('throttle:6,15');

	Route::post('/password/reset', 'resetPassword')->middleware('throttle:5,15');

});

Route::post('/register', [UserController::class, 'store']);
