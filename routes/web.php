<?php

use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


Route::get('/{code}', [UrlRedirectController::class, 'redirect'])->where('code', '[A-Za-z0-9]+');
