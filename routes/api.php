<?php

require __DIR__ . "/auth.php";

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\ImageUploadController;



Route::prefix('user')->middleware('auth:sanctum')->group(function () {


    Route::controller(UserController::class)->group(function () {

        Route::get('/', 'user'); // user informations

        Route::get('/users', 'users')->middleware(['role:admin']); // get all users - admin

        Route::put('/', 'update'); // update name - email

        Route::put('/upadete-password', 'updatePassword'); // change password

        Route::delete('/account', 'deleteAccount'); // remove account - user

        Route::delete('/{id}', 'destroy')->where('id', '^[0-9]+$'); // remove user account - admin

    });
});


Route::post('/upload_photo_profile', ImageUploadController::class)->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {

    Route::controller(UrlController::class)->group(function () {

        Route::get('/urls/{sort?}', 'index')->where('sort', 'desc|asc'); // get all short links

        Route::get('/trash', 'trash'); // get all short links trashed

        Route::post('/urls', 'store'); //->middleware('throttle:20,5'); // add new short link

        Route::get('/urls/{id}', 'show')->where('id', '^[0-9]+$'); // show short link details

        Route::put('/urls/{id}', 'update')->where('id', '^[0-9]+$'); // update short link

        Route::put('/urls/{id}/visual', 'visualUrl')->where('id', '^[0-9]+$');

        Route::patch('/restore_url/{id}', 'restoreUrl')->where('id', '^[0-9]+$'); // restore short link from trash

        Route::delete('/force_delete_url/{id}', 'forceDeleteUrl')->where('id', '^[0-9]+$'); // remove short link

        Route::delete('/urls/{id}', 'destroy')->where('id', '^[0-9]+$'); // move short link to trash

    });

    Route::post('/email/verify', EmailVerifyController::class)->middleware('throttle:5,15');

    Route::get('/search', SearchController::class); // search by title and link 

});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/analytics/{filter?}', [AnalyticsController::class, 'index']);
    Route::get('/locations/{filter?}', [AnalyticsController::class, 'showLocations']);

});
