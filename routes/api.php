<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\ImageUploadController;
use App\Models\User;

Route::get('/user', [UserController::class, 'user'])->middleware('auth:sanctum');

Route::put('/user', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'store']);

Route::post('/upload_photo_profile', [ImageUploadController::class, 'upload'])->middleware('auth:sanctum');


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

    Route::post('/email/verify', [EmailVerifyController::class, 'verify'])->middleware('throttle:2,5');

    Route::get('/search', SearchController::class);
});


Route::middleware('auth:sanctum')->group(function(){

    Route::get('/analytics/{filter?}',[AnalyticsController::class,'index']);

});

Route::get('/test', function () {

    $users = User::with(['urls' => function ($query) {
        $query->select('id', 'user_id','code');
    }])->withCount('urls')->get();

    return response()->json($users);
});
