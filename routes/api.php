<?php

require __DIR__ . "/auth.php";

use App\Enums\FilterType;
use App\Enums\SortUrlEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\ImageUploadController;


Route::prefix('user')->middleware('auth:sanctum')->group(function (): void {

    Route::controller(UserController::class)->group(function () {

        Route::get('/', 'user'); // user information's

        Route::get('/users', 'users')->middleware(['role:admin']); // get all users - admin

        Route::put('/', 'update'); // update name - email

        Route::put('/update-password', 'updatePassword'); // change password

        Route::delete('/account', 'deleteAccount'); // remove account - user

        Route::delete('/{id}', 'destroy'); // remove user account - admin

    });
});


Route::post('/upload_photo_profile', ImageUploadController::class)->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function (): void {

    Route::controller(UrlController::class)->group(function (): void {

        Route::get('/urls/{sort?}', 'index')->whereIn('sort', SortUrlEnum::cases()); // get all short links

        Route::get('/trash', 'trash'); // get all short links trashed

        Route::post('/urls', 'store')->middleware('throttle:20,5'); // add new short link

        Route::get('/urls/{id}', 'show'); // show short link details

        Route::put('/urls/{id}', 'update'); // update short link

        Route::put('/urls/{id}/visual', 'visualUrl');

        Route::patch('/restore_url/{id}', 'restoreUrl'); // restore short link from trash

        Route::delete('/force_delete_url/{id}', 'forceDeleteUrl'); // remove short link

        Route::delete('/urls/{id}', 'destroy'); // move short link to trash

    });


    Route::post('/email/verify', EmailVerifyController::class)->middleware('throttle:5,15');

    Route::get('/search', SearchController::class); // search by title and link 

});


Route::middleware('auth:sanctum')->controller(AnalyticsController::class)->group(function (): void {

    Route::get('/analytics/{filter?}', 'index')->whereIn('filter', FilterType::cases());

    Route::get('/locations/{filter?}', 'showLocations')->whereIn('filter', FilterType::cases());

});




// Test if redis is working
Route::get('/test', function (): JsonResponse {

    $user = Cache::remember('testUser', 20, function (): string {

        sleep(5);

        return 'mehdi';
        
    });

    return response()->json(['name' => $user]);

});