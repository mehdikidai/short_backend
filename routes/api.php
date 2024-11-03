<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\ImageUploadController;



Route::controller(UserController::class)->middleware('auth:sanctum')->group(function () {

    Route::get('/user', 'user');

    Route::get('/users', 'users')->middleware(['role:admin']);

    Route::put('/user', 'update');

    Route::put('/user/upadet/password', 'updatePassword');

    Route::delete('/user/{id}', 'destroy');

    Route::delete('/account', 'delete_account');
});

Route::post('/register', [UserController::class, 'store']);



Route::post('/upload_photo_profile', ImageUploadController::class)->middleware('auth:sanctum');


Route::controller(AuthController::class)->group(function () {

    Route::post('/login', 'login')->middleware('throttle:5,1');

    Route::post('/logout', 'logout')->middleware('auth:sanctum');

    Route::post('/password/send-reset-code', 'sendResetCodeEmail');

    Route::post('/password/reset', 'resetPassword')->middleware('throttle:5,1');


});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(UrlController::class)->group(function () {

        Route::get('/urls', 'index');

        Route::get('/trash', 'trash');

        Route::post('/urls', 'store');

        Route::get('/urls/{id}', 'show');

        Route::put('/urls/{id}', 'update');

        Route::patch('/restore_url/{id}', 'restoreUrl');

        Route::delete('/force_delete_url/{id}', 'forceDeleteUrl');

        Route::delete('/urls/{id}', 'destroy');
    });

    Route::post('/email/verify', [EmailVerifyController::class, 'verify'])->middleware('throttle:2,5');

    Route::get('/search', SearchController::class);
});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/analytics/{filter?}', [AnalyticsController::class, 'index']);
    Route::get('/locations/{filter?}', [AnalyticsController::class, 'showLocations']);
});




//Route::get('/tt',[AnalyticsController::class,'most_countries'])->middleware('auth:sanctum');